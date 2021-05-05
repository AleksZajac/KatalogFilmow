<?php
/**
 * Category controller.
 */

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Class CategoryController.
 *
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \App\Repository\CategoryRepository           $repository Category repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="category_index",
     * )
     */
    public function index(CategoryRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Category::NUMBER_OF_ITEMS
        );
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        return $this->render('category/index.html.twig', ['pagination' => $pagination, 'form' => $form->createView()]);
    }
    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\CategoryRepository           $repository Category repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="category_new",
     * )
     */
    public function new(Request $request, CategoryRepository $repository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($category);
            /*
             * Potwierdzienie zapisania
             */
            $this->addFlash('success', 'message_created_successfully');
            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/new.html.twig',
            ['form' => $form->createView()]
        );
    }

}