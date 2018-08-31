<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;

/**
 * Route("/genre")
 */
class GenreController extends AbstractController
{
    /**
     * Route("/", name="genre_index", methods="GET")
     */
    public function index($page, $filter = null): Response
    {
        //how many authors display per page, you can change it on yor needs
        $paginator=5;
        //initial offset
        $offset=0;

        $repository=$this->getDoctrine()->getRepository(Genre::class);

        //many times faster query count without whole data, assuming huge amount
        $count=$repository->count([]);
        $last_page=(integer)ceil($count/$paginator);

//        dump(['total list length'=>$count, 'possible last page'=> $last_page]);

        //process miss formed requests
        if ($page>$last_page) {
//            dump('reset direct (old, wrong, hacked or forced) page arg');
            $page=$last_page;
        }

        if ($count > $paginator) {
            $offset=($page-1)*$paginator;
//            dump("Enabling pagination for page $page with offset $offset ");
        }

//      process filterByName from doctrine repo (more complex filters suggested via model repo, see more at https://knpuniversity.com/screencast/symfony-rest3/filtering )
//      dump($filter);
        if ($filter) {
            $genres=$repository->createQueryBuilder('genre')
                ->where('genre.name LIKE :name')
                ->setParameter('name', '%'.$filter.'%')
                ->getQuery()
                ->execute();
            $count=count($genres);
        }
        else {
//          findAll changed to limited query https://www.doctrine-project.org/api/orm/2.6/Doctrine/ORM/EntityRepository.html
            $genres = $repository->findBy([], null, $paginator, $offset);
        }

//        dump(['method'=>__CLASS__, 'page'=>$page, 'displayed list length'=>count($genres)]);
        return $this->render('genre/index.html.twig', [
            'genres' => $genres,
            'ion_pager'=>[
                'per_page'=>$paginator,
                'total_count'=>$count,
                'rendered_page_index'=>$page
            ],
            'filter' => $filter
        ]);
    }

    /**
     * Route("/new", name="genre_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($genre);
            $em->flush();

            return $this->redirectToRoute('genres');
        }

        return $this->render('genre/new.html.twig', [
            'genre' => $genre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Route("/{id}", name="genre_show", methods="GET")
     */
    public function show($id): Response
    {
        $genre=$this->getDoctrine()->getRepository(Genre::class)->findOneBy(['id'=>$id]);
        return $this->render('genre/show.html.twig', ['genre' => $genre]);
    }

    /**
     * Route("/{id}/edit", name="genre_edit", methods="GET|POST")
     */
    public function edit(Request $request, Genre $genre, $id): Response
    {
        $genre=$this->getDoctrine()->getRepository(Genre::class)->findOneBy(['id'=>$id]);
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('genre_edit', ['id' => $genre->getId()]);
        }

        return $this->render('genre/edit.html.twig', [
            'genre' => $genre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Route("/{id}", name="genre_delete", methods="DELETE")
     */
    public function delete(Request $request, $id): Response
    {
        $genre=$this->getDoctrine()->getRepository(Genre::class)->findOneBy(['id'=>$id]);
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($genre);
            $em->flush();
        }

        return $this->redirectToRoute('genres');
    }
}
