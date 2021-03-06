<?php
/**
 * Avatar controller.
 */

namespace App\Controller;

use App\Entity\Avatar;
use App\Form\AvatarType;
use App\Repository\AvatarRepository;
use App\Service\FileUploader;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AvatarController.
 *
 * @Route("/avatar")
 */
class AvatarController extends AbstractController
{
    /**
     * Avatar repository.
     *
     * @var AvatarRepository
     */
    private $avatarRepository;

    /**
     * File uploader.
     *
     * @var FileUploader
     */
    private $fileUploader;

    /**
     * Filesystem component.
     *
     * @var Filesystem
     */
    private $filesystem;

    /**
     * AvatarController constructor.
     *
     * @param AvatarRepository $avatarRepository Avatar repository
     * @param Filesystem       $filesystem       Filesystem component
     * @param FileUploader     $fileUploader     File uploader
     */
    public function __construct(AvatarRepository $avatarRepository, Filesystem $filesystem, FileUploader $fileUploader)
    {
        $this->avatarRepository = $avatarRepository;
        $this->filesystem = $filesystem;
        $this->fileUploader = $fileUploader;
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     name="avatar_create",
     *     methods={"GET", "POST"}
     * )
     */
    public function create(Request $request): Response
    {
        if ($this->getUser()->getAvatar()) {
            return $this->redirectToRoute(
                'avatar_edit',
                ['id' => $this->getUser()->getAvatar()->getId()]
            );
        }
        $avatar = new Avatar();
        $form = $this->createForm(AvatarType::class, $avatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFilename = $this->fileUploader->upload(
                $form->get('file')->getData()
            );
            $avatar->setUser($this->getUser());
            $avatar->setFilename($avatarFilename);
            $this->avatarRepository->save($avatar);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('profil_index');
        }

        return $this->render(
            'avatar/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Avatar  $avatar  Avatar
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     name="avatar_edit",
     *     methods={"GET", "POST"}
     * )
     */
    public function edit(Request $request, Avatar $avatar): Response
    {
        if (!$this->getUser()->getAvatar()) {
            return $this->redirectToRoute('avatar_create');
        }
        $form = $this->createForm(AvatarType::class, $avatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->filesystem->remove(
                $this->getParameter('avatars_directory').'/'.$avatar->getFilename()
            );
            $avatarFilename = $this->fileUploader->upload(
                $form->get('file')->getData()
            );
            $avatar->setFilename($avatarFilename);
            $this->avatarRepository->save($avatar);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('profil_index');
        }

        return $this->render(
            'avatar/edit.html.twig',
            [
                'form' => $form->createView(),
                'avatar' => $avatar,
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param Request          $request    HTTP request
     * @param Avatar           $avatar     Tag entity
     * @param AvatarRepository $repository Tag repository
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
     *     name="avatar_delete",
     * )
     */
    public function delete(Request $request, Avatar $avatar, AvatarRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $avatar, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($avatar);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('profil_index');
        }

        return $this->render(
            'avatar/delete.html.twig',
            [
                'form' => $form->createView(),
                'avatar' => $avatar,
            ]
        );
    }
}
