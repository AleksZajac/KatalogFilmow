<?php
/**
 * Films controller.
 */

namespace App\Controller;

use App\Entity\FavoriteMovies;
use App\Entity\Films;
use App\Form\FavoriteMoviesType;
use App\Repository\FavoriteMoviesRepository;
use App\Service\FavoriteService;
use App\Service\FilmsService;
use App\Service\UserService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FilmsController.
 *
 * @Route("/favorite")
 *
 * @IsGranted("ROLE_USER")
 */
class FavoriteMovieController extends AbstractController
{
    /**
     * Favorite service.
     *
     * @var FavoriteService
     */
    private $favoriteService;
    /**
     * Favorite service.
     *
     * @var FilmsService
     */
    private $filmsService;
    /**
     * Favorite service.
     *
     * @var UserService
     */
    private $userService;
    /**
     * Favorite service.
     *
     * @var FavoriteMoviesRepository
     */
    private $favoriteMoviesRepository;

    /**
     * CategoryController constructor.
     *
     * @param FavoriteService          $favoriteService          Favorite service
     * @param FilmsService             $filmsService
     * @param UserService              $userService
     *
     * @param FavoriteMoviesRepository $favoriteMoviesRepository
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
     * @param Request            $request
     * @param PaginatorInterface $paginator
     *
     * @return Response HTTP response
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
     * @param Request $request HTTP request*
     * @param Films   $films
     * @param int     $id
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
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
        $thisuser = $this->getUser();
        //if ($this->favoriteMoviesRepository->findBy(['id_user' => $userr,'id_film' => $id])){
        //    $this->addFlash('success', 'message_call ready on list');

        //    return $this->redirectToRoute('favorite_index');
        //}
        if ($this->favoriteService->findByuser($userr)) {
            $favorite = $this->favoriteService->findOneByuser($userr);
        } else {
            $favorite = new FavoriteMovies();
            $favorite->setUser($thisuser);
        }
        $films = $this->filmsService->showFilms($id);
        $user = $this->userService->showUser($userr);
        $form = $this->createForm(FavoriteMoviesType::class, $favorite);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $favorite->addFilm($films);
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
     * @param Request                  $request            HTTP request
     * @param FavoriteMovies           $favorite           Category entity
     * @param FavoriteMoviesRepository $favoriteRepository Category repository
     * @param Films                    $films
     * @param int                      $id
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
     *     name="favorite_delete",
     * )
     */
    public function delete(Request $request, FavoriteMovies $favorite, FavoriteMoviesRepository $favoriteRepository, Films $films, int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $exiFav = $films->getFavoriteMovies();
        $form = $this->createForm(FormType::class, $favorite, ['method' => 'DELETE']);
        $form->handleRequest($request);
        $films = $em->getRepository(Films::class)->find($id);
        $user = $this->getUser();
        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $favoritemovie = $films->getFavoriteMovies();
            $this->favoriteService->delete($favoritemovie);
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
