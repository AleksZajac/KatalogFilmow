<?php
/**
 * Films controller.
 */

namespace App\Controller;

use App\Entity\FavoriteMovies;
use App\Entity\Films;
use App\Form\FavoriteMoviesType;
use App\Repository\FavoriteMoviesRepository;
use App\Repository\FilmsRepository;
use App\Service\FavoriteService;
use App\Service\FilmsService;
use App\Service\UserService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FilmsController.
 *
 * @Route("/favorite")
 */
class FavoriteMovieController extends AbstractController
{
    /**
     * Favorite service.
     *
     * @var \App\Service\FavoriteService
     */
    private $favoriteService;
    /**
     * Favorite service.
     *
     * @var \App\Service\FilmsService
     */
    private $filmsService;
    /**
     * Favorite service.
     *
     * @var \App\Service\UserService
     */
    private $userService;
    /**
     * Favorite service.
     *
     * @var \App\Repository\FavoriteMoviesRepository
     */
    private $favoriteMoviesRepository;

    /**
     * CategoryController constructor.
     *
     * @param \App\Service\FavoriteService $favoriteService Favorite service
     */
    public function __construct(FavoriteService $favoriteService, FilmsService $filmsService, UserService $userService, FavoriteMoviesRepository $favoriteMoviesRepository)
    {
        $this->favoriteService = $favoriteService;
        $this->filmsService = $filmsService;
        $this->userService = $userService;
        $this->favoriteMoviesRepository = $favoriteMoviesRepository;
    }

    /**
     * Index action.
     *
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator Paginator
     * @param \Symfony\Component\HttpFoundation\Request $request   HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="favorite_index",
     * )
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);
        $user = $this->getUser()->getId();
        $pagination = $this->favoriteService->createPaginatedList($page, $user);

        return $this->render(
            'favorite/index.html.twig',
            ['pagination' => $pagination,
            ]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request*
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET", "POST"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="favorite_new",
     * )
     */
    public function new(Request $request, Films $films, int $id): Response
    {
        $userr = $this->getUser()->getId();
        //if ($this->favoriteMoviesRepository->findBy(['id_user' => $userr]) and $this->favoriteMoviesRepository->findBy(['id_film' => $id])) {
          //  $this->addFlash('success', 'message_call ready on list');

        //    return $this->redirectToRoute('favorite_index');
       // }
        if ($this->favoriteMoviesRepository->findBy(['id_user' => $userr])) {
            $favorite = $this->favoriteMoviesRepository->findOneBy(['id_user' => $userr]);
        } else {
            $favorite = new FavoriteMovies();
        }
        $films = $this->filmsService->showFilms($id);
        $user = $this->userService->showUser($userr);
        $form = $this->createForm(FavoriteMoviesType::class, $favorite);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $favorite->addIdFilm($films);
            $this->favoriteService->save($favorite);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('favorite_index');
        }

        return $this->render(
            'favorite/new.html.twig',
            ['form' => $form->createView(),
                'id' => $id, ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\FavoriteMovies                $favorite           Category entity
     * @param \App\Repository\FavoriteMoviesRepository  $favoriteRepository Category repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="favorite_delete",
     * )
     */
    public function delete(Request $request, FavoriteMovies $favorite, FavoriteMoviesRepository $favoriteRepository, Films $films, int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $exiFav = $films->getFavoriteMovies();
        $form = $this->createForm(FormType::class, $favorite, ['method' => 'DELETE']);
        $form->handleRequest($request);
        $films = $em->getRepository(FilmsRepository::class)->find($id);
        $user = $this->getUser();
        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $favoritemovie = $films->getFavoriteMovies();
            $favoriteRepository->delete($favoritemovie);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('favorite_index');
        }

        return $this->render(
            'favorite/delete.html.twig',
            [
                'form' => $form->createView(),
                'favorite' => $favorite,
            ]
        );
    }
}
