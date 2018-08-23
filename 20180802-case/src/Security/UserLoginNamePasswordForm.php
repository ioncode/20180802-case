<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;


//http://symfony.com/doc/current/security/guard_authentication.html

class UserLoginNamePasswordForm extends AbstractFormLoginAuthenticator
{
    private $csrfTokenManager;
    private $security;
    private $encoder;
    public function __construct(CsrfTokenManagerInterface $csrfTokenManager, Security $security, UserPasswordEncoderInterface $encoder)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->security = $security;
        $this->encoder = $encoder;
    }
    public function supports(Request $request)
    {
//        don't forget to comment all dumps for preventing session invalid!!!
//        dump(['method'=>__METHOD__, 'POST'=>$request->isMethod('POST'), 'logged_in_security_user'=>$this->security->getUser()]);

//        let's guard only POST requests, because we want to guard FORMS submissions
        if ($request->isMethod('POST') && $request->attributes->get('_route') === 'login') {
            return true;


        }
        else {

//            dump('I don't want to use '.__CLASS__.' guard on GET requests, proceed another authenticators');

        }
//        According 2 https://symfony.com/doc/current/best_practices/security.html let me disable this feature for all other routes, unless login

        if ($this->security->getUser()) {

            return false;
            //@TODO redirect logged in user from login route to profile

        }
        return false;
    }

    public function getCredentials(Request $request)
    {



//        0) handle CSRF token in POST request

        $csrfToken = $request->request->get('_csrf_token');





//        dump(['checking credentials and return them or exception', $csrfToken, $request->request->get('_name')]);
        if (false === $this->csrfTokenManager->isTokenValid(new CsrfToken('authenticate', $csrfToken))) {
            throw new InvalidCsrfTokenException('Invalid CSRF token.');
        }
        else {

//            dump('CSRF token is valid');
        }

//        dump([$this->csrfTokenManager, $this->security->getUser(), 'csrfToken'=>$csrfToken, 'username'=>$request->request->get('_name')]);


//        @TODO 1)  let me use form factory & handlers instead raw form



        return [
            'csrfToken'=>$csrfToken,
            'username'=>$request->request->get('_name'),
            'password'=>$request->request->get('_password')
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
//        dump(['method'=>__METHOD__, $credentials, $userProvider, $this->security->getUser()]);
//        @TODO see getCredentials or try another solution






        return $userProvider->loadUserByUsername($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {

//        dd($this->encoder->isPasswordValid($user, $credentials['password']));

        return $this->encoder->isPasswordValid($user, $credentials['password']);



//        dump([__METHOD__, $credentials, $user]);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {

//        dump(['method'=>__METHOD__, $request->getSession(), $exception]);
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
//        dump([__CLASS__, 'pick up notice or any other stuff after successful auth via guard listener']);

//        @TODO add apiKey token in response's HEAD

        //simple redirect to profile
        return new RedirectResponse('profile', 303);
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


    protected function getLoginUrl()
    {
        return '/login';
    }
}
