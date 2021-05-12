<?php
/**
 * Films controller.
 */

namespace App\Controller;

use App\Entity\Films;
use App\Form\FilmsType;
use App\Repository\CommentsRepository;
use App\Repository\FilmsRepository;
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
     * Index action.
     *
     * @param \App\Repository\FilmsRepository           $repository Films repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="films_index",
     * )
     */
    public function index(FilmsRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Films::NUMBER_OF_ITEMS
        );
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        return $this->render('films/index.html.twig', ['pagination' => $pagination, 'form' => $form->createView()]);
    }

    /**
     * View action.
     *
     * @param \App\Entity\Films                         $film      Film Entity
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator Paginator
     * @param \Symfony\Component\HttpFoundation\Request $request   HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="film_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Films $film, FilmsRepository $filmsRepository, CommentsRepository $commentsRepository, PaginatorInterface $paginator, Request $request, $id): Response
    {
        $pagination = $paginator->paginate(
            $filmsRepository->queryAll(),
            $request->query->getInt('page', 1),
            Films::NUMBER_OF_ITEMS
        );
        $comments = $film->getComment();

        return $this->render(
            'films/view.html.twig',
            [
                'film' => $film,
                'comments' => $comments,
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\FilmsRepository           $repository Films repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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
            $repository->save($film);
            /*
             * Potwierdzienie zapisania
             */
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
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Films                         $film      Films entity
     * @param \App\Repository\FilmsRepository           $repository Films repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="films_edit",
     * )
     *
     */
    public function edit(Request $request, Films $film, FilmsRepository $repository): Response
    {
        $form = $this->createForm(FilmsType::class, $film, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($film);

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
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Films                         $film      Film entity
     * @param \App\Repository\FilmsRepository           $repository Film repository
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
     *     name="films_delete",
     * )
     */
    public function delete(Request $request, Films $film, FilmsRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $film, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($film);
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
}
