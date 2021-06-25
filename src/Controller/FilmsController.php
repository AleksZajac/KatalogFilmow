<?php
/**
 * Films controller.
 */

namespace App\Controller;

use App\Entity\Films;
use App\Form\FilmsType;
use App\Repository\CommentsRepository;
use App\Repository\FilmsRepository;
use App\Service\CategoryService;
use App\Service\FilmsService;
use App\Service\TagService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FilmsController.
 *
 * @Route("/films")
 */
class FilmsController extends AbstractController
{
    /**
     * Film service.
     *
     * @var FilmsService
     */
    private $filmsService;
    /**
     * Category service.
     *
     * @var CategoryService
     */
    private $categoryService;
    /**
     * Tag service.
     *
     * @var TagService
     */
    private $tagService;

    /**
     * CategoryController constructor.
     *
     * @param FilmsService $filmsService Category service
     */
    public function __construct(FilmsService $filmsService, CategoryService $categoryService, TagService $tagService)
    {
        $this->filmsService = $filmsService;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Index action.
     *
     * @param PaginatorInterface $paginator Paginator
     * @param Request $request   HTTP request
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="films_index",
     * )
     */
    public function index(Request $request, FilmsRepository $repository, PaginatorInterface $paginator): Response
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');

        $pagination = $this->filmsService->createPaginatedList(
            $request->query->getInt('page', 1),
            $filters
        );
        $category = $this->categoryService->allCategory();
        $tag = $this->tagService->allTag();
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pagination = null;
            $title = $form->getData();
            $pagination = $paginator->paginate(
                $repository->queryByTitle($title),
                $request->query->getInt('page', 1),
                Films::NUMBER_OF_ITEMS
            );


            return $this->render(
                'films/searchView.html.twig',
                ['pagination' => $pagination]
            );
        }

        return $this->render(
            'films/index.html.twig',
            ['pagination' => $pagination,
                'category' => $category,
                'tag' => $tag,
                'form' => $form->createView(),
                ]
        );
    }

    /**
     * View action.
     *
     * @param Films $film Film Entity*
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="film_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Films $film, CommentsRepository $commentsRepository, Request $request, $id): Response
    {
        $comments = $film->getComment();

        return $this->render(
            'films/view.html.twig',
            [
                'film' => $film,
                'comments' => $comments,
            ]
        );
    }

    /**
     * New action.
     *
     * @param Request $request HTTP request*
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="film_new",
     * )
     */
    public function new(Request $request, FilmsRepository $repository): Response
    {
        $film = new Films();
        $form = $this->createForm(FilmsType::class, $film);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->filmsService->save($film);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('films_index');
        }

        return $this->render(
            'films/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Films $film    Films entity*
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="films_edit",
     * )
     */
    public function edit(Request $request, Films $film): Response
    {
        $form = $this->createForm(FilmsType::class, $film, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->filmsService->save($film);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('films_index');
        }

        return $this->render(
            'films/edit.html.twig',
            [
                'form' => $form->createView(),
                'film' => $film,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request    HTTP request
     * @param Films $film       Film entity
     * @param FilmsRepository $repository Film repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="films_delete",
     * )
     */
    public function delete(Request $request, Films $film): Response
    {
        $form = $this->createForm(FormType::class, $film, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->filmsService->delete($film);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('films_index');
        }

        return $this->render(
            'films/delete.html.twig',
            [
                'form' => $form->createView(),
                'film' => $film,
            ]
        );
    }

    /**
     * Search action.
     *
     * @param Films $film Film entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/search",
     *      methods={"GET", "POST"},
     *     name="film_search",
     * )
     */
    public function searchForm(Request $request, PaginatorInterface $paginator, FilmsRepository $repository): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pagination = null;
            $title = $form->getData();
            $pagination = $paginator->paginate(
                $repository->queryByTitle($title),
                $request->query->getInt('page', 1),
                Films::NUMBER_OF_ITEMS
            );

            return $this->render(
                'films/searchView.html.twig',
                ['pagination' => $pagination,
                    'title' => $title,
                ]
            );
        }

        return $this->render(
            'films/search.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
