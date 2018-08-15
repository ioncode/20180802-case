<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;

class UserLoginNamePasswordForm extends AbstractFormLoginAuthenticator
{
    private $csrfTokenManager;
    private $security;
    public function __construct(CsrfTokenManagerInterface $csrfTokenManager, Security $security)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->security = $security;
    }
    public function supports(Request $request)
    {
        //disable doublecheck 4 reg form
        //dump('Login/register route support checker');
//        According 2 https://symfony.com/doc/current/best_practices/security.html let me disable this feature for all other routes, unless login
        if ($request->attributes->get('_route') === 'register' && $request->isMethod('POST')) {


            return false;
        }

        if ($request->attributes->get('_route') === 'profile') {

            return false;
        }




        if ($request->attributes->get('_route') === 'login' || $request->attributes->get('_route') === 'register') {
            //dump('Login/register route support checker');
            //dump($this->security->getUser());
            if ($request->isMethod('POST') ) {
                //dump('Post request in login/register');
                return true;
            }


            return false;

        }
        if ($this->security->getUser()) {
            //dump([$request->attributes->get('_route'), $this->security->getUser()]);
                        return false;
        }
        if ($request->attributes->get('_route') !== 'login' || !$request->isMethod('POST')) {
            return true;
        }

        return true;
    }

    public function getCredentials(Request $request)
    {
        $csrfToken = $request->request->get('_csrf_token');

        if (false === $this->csrfTokenManager->isTokenValid(new CsrfToken('authenticate', $csrfToken))) {
            throw new InvalidCsrfTokenException('Invalid CSRF token.');
        }

        dump([$this->csrfTokenManager, $this->security->getUser()]);

    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiKey = $credentials['token'];

        if (null === $apiKey) {
            return;
        }

        // if a User object, checkCredentials() is called
        return $userProvider->loadUserByUsername($apiKey);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array(
            // you might translate this message
            'message' => 'Authentication Required 4 this request'
        );

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * Return the URL to the login page.
     *
     * @return string
     */
    protected function getLoginUrl()
    {
        return '/login';
    }
}
