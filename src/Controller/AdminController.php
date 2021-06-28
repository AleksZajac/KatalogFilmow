<?php
/**
 * AdminController.
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\FavoriteMoviesRepository;
use App\Repository\UserRepository;
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
 * Class AdminController.
 *
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * Favorite service.
     *
     * @var \App\Repository\FavoriteMoviesRepository
     */
    private $favoriteMoviesRepository;
    /**
     * Favorite service.
     *
     * @var \App\Service\UserService
     */
    private $userService;

    /**
     * CategoryController constructor.
     */
    public function __construct(UserService $userService, FavoriteMoviesRepository $favoriteMoviesRepository)
    {
        $this->userService = $userService;
        $this->favoriteMoviesRepository = $favoriteMoviesRepository;
    }

    /**
     * Index action.
     *
     * @param Request            $request    HTTP request
     * @param UserRepository     $repository Repository
     * @param PaginatorInterface $paginator  Paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="admin_index",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request, UserRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            User::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Delete action.
     *
     * @param Request        $request    HTTP request
     * @param User           $user       User entity
     * @param UserRepository $repository User repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/deleteUser",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_delete",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteUser(Request $request, User $user, UserRepository $repository, int $id, FavoriteMoviesRepository $favoriteMoviesRepository): Response
    {
        $form = $this->createForm(FormType::class, $user, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->favoriteMoviesRepository->findBy(['id_user' => $id])) {
                $favorite = $this->favoriteMoviesRepository->findOneBy(['id_user' => $id]);
                $favoriteMoviesRepository->delete($favorite);
            }

            $repository->delete($user);
            $this->addFlash('success', 'success.deletedsuccessfully');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render(
            'admin/deleteUser.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Add Admin action.
     *
     * @param Request        $request    HTTP request
     * @param User           $user       User entity
     * @param UserRepository $repository User repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/AddAdmin",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="add_admin",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function addAdmin(Request $request, User $user, UserRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($request->isMethod('PUT') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
            $repository->save($user);
            $this->addFlash('success', 'success.add_Admin_successfully');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render(
            'admin/AddAdmin.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
