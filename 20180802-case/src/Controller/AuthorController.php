<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//move routing to config/routes.yaml
//use Symfony\Component\Routing\Annotation\Route;


/**
 * Route("/author")
 */
class AuthorController extends Controller
{
    /**
     * Route("/", name="author_index", methods="GET")
     */
    public function index($page, $filter = null): Response
    {
        //how many authors display per page, you can change it on yor needs
        $paginator=5;
        //initial offset
        $offset=0;

        $repository=$this->getDoctrine()->getRepository(Author::class);

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
//            $authors = $repository->findBy(['name'=>$filter], null, $paginator, $offset);
//            try find 'bob' in "this is bob's name" ;), see example here https://gyazo.com/bdbab15010c233da6c74799b5f9a8f4c
//            dump([$authors, $repository->findOneBy(['name'=>$filter]), $repository->createQueryBuilder('author')
//                ->where('author.name LIKE :name')
//                ->setParameter('name', '%'.$filter.'%')
//                ->getQuery()
//                ->execute()
//            ]);
            $authors=$repository->createQueryBuilder('author')
                ->where('author.name LIKE :name')
                ->setParameter('name', '%'.$filter.'%')
                ->getQuery()
                ->execute();
            $count=count($authors);
        }
        else {
//          findAll changed to limited query https://www.doctrine-project.org/api/orm/2.6/Doctrine/ORM/EntityRepository.html
            $authors = $repository->findBy([], null, $paginator, $offset);
        }

//        dump(['method'=>__CLASS__, 'page'=>$page, 'displayed list length'=>count($authors)]);
        return $this->render('author/index.html.twig', [
            'authors' => $authors,
            'ion_pager'=>[
                'per_page'=>$paginator,
                'total_count'=>$count,
                'rendered_page_index'=>$page
            ],
            'filter' => $filter
        ]);
    }

    /**
     * Route("/new", name="author_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('authors');
        }

        return $this->render('author/new.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Route("/{id}", name="author_show", methods="GET")
     */
//    @ParamConverter("id", class="Author")
// i can't find any ability to convert passed ID to doctrine object via YAML routing (without Route annotations or heavy & not always needed auto convert via app/config/config.yml, see https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html ), so i must convert passed id inside action
    public function show(Author $author, $id): Response
    {
//        dump([$this->getDoctrine()->getRepository(Author::class)->findOneBy(['id'=>$id]), $id]);
        $author=$this->getDoctrine()->getRepository(Author::class)->findOneBy(['id'=>$id]);
        return $this->render('author/show.html.twig', ['author' => $author]);
    }

    /**
     * Route("/{id}/edit", name="author_edit", methods="GET|POST")
     */
    public function edit(Request $request, Author $author, $id): Response
    {
        $author=$this->getDoctrine()->getRepository(Author::class)->findOneBy(['id'=>$id]);
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('author_edit', ['id' => $author->getId()]);
        }

        return $this->render('author/edit.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Route("/{id}", name="author_delete", methods="DELETE")
     */
    public function delete(Request $request, Author $author, $id): Response
    {
        $author=$this->getDoctrine()->getRepository(Author::class)->findOneBy(['id'=>$id]);

//        dd($author);

        if ($author && $this->isCsrfTokenValid('delete'.$author->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($author);
            $em->flush();
        }



        return $this->redirectToRoute('authors');
    }
}
