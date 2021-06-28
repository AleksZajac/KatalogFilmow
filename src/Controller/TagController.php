<?php
/**
 * Tag controller.
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Form\TagTypeedit;
use App\Repository\TagRepository;
use App\Service\TagService;
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
 * Class TagController.
 *
 * @Route("/tag")
 */
class TagController extends AbstractController
{
    /**
     * Category service.
     *
     * @var TagService
     */
    private $tagService;

    /**
     * CategoryController constructor.
     *
     * @param TagService $tagService Tag service
     */
    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * Index action.
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="tag_index",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(TagRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->tagService->createPaginatedList($page);

        return $this->render(
            'tag/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * New action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="tag_new",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, TagRepository $repository): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render(
            'tag/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Tag     $tag     Tag entity
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
     *     name="tag_delete",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Tag $tag, TagRepository $repository): Response
    {
        if ($tag->getFilms()->count()) {
            $this->addFlash('warning', 'message_tag_contains_films');

            return $this->redirectToRoute('tag_index');
        }
        $form = $this->createForm(FormType::class, $tag, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->delete($tag);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render(
            'tag/delete.html.twig',
            [
                'form' => $form->createView(),
                'tag' => $tag,
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param Request       $request    HTTP request
     * @param Tag           $tag        Films entity
     * @param TagRepository $repository Films repository
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
     *     name="tag_edit",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Tag $tag, TagRepository $repository): Response
    {
        $form = $this->createForm(TagTypeedit::class, $tag, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render(
            'tag/edit.html.twig',
            [
                'form' => $form->createView(),
                'tag' => $tag,
            ]
        );
    }
}
