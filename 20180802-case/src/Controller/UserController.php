<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
//use Symfony\Component\Routing\Annotation\Route;


class UserController extends Controller
{

    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', ['users' => $userRepository->findAll()]);
    }


    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    public function profile(User $user, AuthorizationCheckerInterface $authChecker): Response
    {
        // https://symfony.com/doc/current/controller/argument_value_resolver.html
        dump([$user, $authChecker, 'profile resolver ']);
        if (false === $authChecker->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('Unable to access this page!');
        }


        return $this->render('user/show.html.twig', ['user' => $user]);
    }
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //dump($authenticationUtils);
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        //dump([$error, $lastUsername]);



        return $this->render('user/login.html.twig', ['name' => $lastUsername, 'error' => $error]);
    }
    public function logout(User $user, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        dump([$error, $lastUsername]);



        return $this->render('user/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
