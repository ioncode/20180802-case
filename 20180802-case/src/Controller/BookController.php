<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;

/**
 * Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * Route("/", name="book_index", methods="GET")
     */
    public function index($page, $filter = null): Response
    {



        //how many authors display per page, you can change it on yor needs
        $paginator=10;
        //initial offset
        $offset=0;

        $repository=$this->getDoctrine()->getRepository(Book::class);

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
            $books=$repository->createQueryBuilder('book')
                ->where('book.title LIKE :name')
                ->setParameter('name', '%'.$filter.'%')
                ->orderBy('book.catalog_date', 'DESC')
                ->getQuery()
                ->execute();
            $count=count($books);
        }
        else {
//          findAll changed to limited query https://www.doctrine-project.org/api/orm/2.6/Doctrine/ORM/EntityRepository.html
            $books = $repository->findBy([], ['catalog_date'=>'desc'], $paginator, $offset);
        }

//        dump(['method'=>__CLASS__, 'page'=>$page, 'displayed list length'=>count($genres)]);
        return $this->render('book/index.html.twig', [
            'books' => $books,
            'ion_pager'=>[
                'per_page'=>$paginator,
                'total_count'=>$count,
                'rendered_page_index'=>$page
            ],
            'filter' => $filter
        ]);

    }

    /**
     * Route("/new", name="book_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('books');
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Route("/{id}", name="book_show", methods="GET")
     */
    public function show(Book $book, $id): Response
    {
        $book=$this->getDoctrine()->getRepository(Book::class)->findOneBy(['id'=>$id]);
//        dump($book);
        return $this->render('book/show.html.twig', ['book' => $book]);
    }

    /**
     * Route("/{id}/edit", name="book_edit", methods="GET|POST")
     */
    public function edit(Request $request, Book $book, $id): Response
    {
        $book=$this->getDoctrine()->getRepository(Book::class)->findOneBy(['id'=>$id]);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_edit', ['id' => $book->getId()]);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Route("/{id}", name="book_delete", methods="DELETE")
     */
    public function delete(Request $request, Book $book): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($book);
            $em->flush();
        }

        return $this->redirectToRoute('book_index');
    }
}
