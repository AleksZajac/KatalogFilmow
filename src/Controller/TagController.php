<?php
/**
 * Tag controller.
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Class TagController.
 *
 * @Route("/tag")
 */
class TagController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \App\Repository\TagRepository           $repository Tag repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="tag_index",
     * )
     */
    public function index(TagRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Tag::NUMBER_OF_ITEMS
        );
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        return $this->render('tag/index.html.twig', ['pagination' => $pagination, 'form' => $form->createView()]);
    }
    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\TagRepository           $repository Tag repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="tag_new",
     * )
     */
    public function new(Request $request, TagRepository $repository): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($tag);
            /*
             * Potwierdzienie zapisania
             */
            $this->addFlash('success', 'message_created_successfully');
            return $this->redirectToRoute('tag_index');
        }

        return $this->render(
            'tag/new.html.twig',
            ['form' => $form->createView()]
        );
    }
}