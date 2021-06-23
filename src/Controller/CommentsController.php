<?php
/**
 * Comments controller.
 */

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Films;
use App\Form\CommentType;
use App\Repository\CommentsRepository;
use App\Repository\FilmsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FilmsController.
 *
 * @Route("/comments")
 */
class CommentsController extends AbstractController
{
    /**
     * }
     * Add comment action.
     *
     * @param \App\Entity\Comments $comments Trash entity
     * @param \App\Entity\Films $films Trash entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/add_comment/{id}",
     *      methods={"GET", "POST"},
     *     name="add_comment",
     * )
     */
    public function addcommentForm(Request $request, PaginatorInterface $paginator, CommentsRepository $repository, FilmsRepository $filmsRepository,$id): Response
    {
        $comment = new Comments();
        $entityManager = $this->getDoctrine()->getManager();
        $films = $entityManager->getRepository(Films::class)->find($id);
        $comment->setFilms($films);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user= $this->getUser()->getUsersprofile();
            $comment->setLogin($user);
            $repository->save($comment);
            /*
            * Potwierdzienie zapisania
            */
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('films_index');
            /*
             * Zrobic powrot do skomentowanego filmu
             */
        }

        return $this->render(
            'comments/add_comment.html.twig',
            ['form' => $form->createView(),
                'id' => $id
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Comments                         $comments      Comments entity
     * @param \App\Repository\CommentsRepository           $repository Comments repository
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
     *     name="comment_delete",
     * )
     */
    public function delete(Request $request, Comments $comments, CommentsRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $comments, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($comments);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('films_index');
        }

        return $this->render(
            'comments/delete.html.twig',
            [
                'form' => $form->createView(),
                'comments' => $comments,
            ]
        );
    }
}
