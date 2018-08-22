<?php
/**
 * Created by PhpStorm.
 * User: Андрей Сергеевич
 * Date: 17.08.2018
 * Time: 15:41
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
//https://symfony.com/doc/current/security.html#securing-controllers-and-other-code
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurityController extends AbstractController
{
    public function login(AuthenticationUtils $authenticationUtils, AuthorizationCheckerInterface $authChecker)
    {



        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        $user_logged_in=$authChecker->isGranted('ROLE_USER');


//        process form_login listener after guard cancel

//        @TODO submit this scenario to https://symfony.com/doc/current/security/form_login.html


        if ($user_logged_in) {
//        @TODO: ask review team to realize this via YAML security config, of course it's possible like access_control ACK
            return $this->redirectToRoute('profile', array(), 303);



        }


//        dump(['method'=>__METHOD__, 'not anon.'=> $user_logged_in]);



        //dump([__CLASS__, 'proceed render login form']);

        return $this->render('user/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
}