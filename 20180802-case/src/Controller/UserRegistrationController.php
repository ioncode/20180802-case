<?php

namespace App\Controller;

//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\UserRegistrationLoginPasswordType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserRegistrationController extends Controller
{

    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, AuthorizationCheckerInterface $authChecker)
    {

        // 0) check if user logged in (not anon.) & logout him. If need another scenario, please, look to my login's process  implementation

        $user_logged_in=$authChecker->isGranted('ROLE_USER');
        //dump(['method'=>__METHOD__, 'not anon.'=> $user_logged_in, 'request_session'=>$request->getSession()]);

        if ($user_logged_in) {


            $this->get('security.token_storage')->setToken(null);
            $request->getSession()->invalidate();


        }

        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserRegistrationLoginPasswordType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {



            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getRawPassword());
            $user->setPassword($password);


            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // 5) set user token
            $token = new UsernamePasswordToken(
                $user,
                $password,
                'main',
                $user->getRoles()
            );
            //dd([$form, $user, $request, $password, $token]);
            $this->get('security.token_storage')->setToken($token);

            // 6) set session
            //
            $this->get('session')->set('_security_main', serialize($token));
            $this->addFlash('success', 'You are now successfully registered!');
            //
            //
            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('profile');
        }
        //dd($form);
        return $this->render(
            'user/new.html.twig',
            array('form' => $form->createView())
        );
    }

}
