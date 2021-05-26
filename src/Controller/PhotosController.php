<?php
/**
 * Avatar controller.
 */

namespace App\Controller;

use App\Entity\Films;
use App\Entity\Photo;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use App\Service\FileUp;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhotosController.
 *
 * @Route("/photo")
 */
class PhotosController extends AbstractController
{
    /**
     * Photos repository.
     *
     * @var \App\Repository\PhotoRepository
     */
    private $photoRepository;

    /**
     * File uploader.
     *
     * @var \App\Service\FileUp
     */
    private $fileUp;
    /**
     * Filesystem component.
     *
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $filesystem;

    /**
     * PhotosController constructor.
     *
     * @param \App\Repository\PhotoRepository $photoRepository Avatar repository
     * @param \App\Service\FileUp             $fileUp          File uploader
     */
    public function __construct(PhotoRepository $photoRepository, FileUp $fileUp, Filesystem $filesystem)
    {
        $this->photoRepository = $photoRepository;
        $this->fileUp = $fileUp;
        $this->filesystem = $filesystem;
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/createphotos/{id}",
     *     name="photos_create",
     *     methods={"GET", "POST"}
     * )
     */
    public function create(Request $request, $id, Films $film, PhotoRepository $repository): Response
    {
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFilename = $this->fileUp->upload(
                $form->get('file')->getData()
            );
            $photo->setFilename($photoFilename);
            $photo->setFilms($film);
            $repository->save($photo);
            //$photoFilename = $this->fileUp->upload(
            //    $form->get('file')->getData()
            //);
            //$film->getId($id);
            //$photo->setFilms($film);
            //$photo->setFilename($photoFilename);
            //$this->photoRepository->save($photo);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('films_index');
        }

        return $this->render(
            'photo/create.html.twig',
            ['form' => $form->createView(),
                'film' => $film, ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Photo                         $photo   Photo
     * @param
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     name="photo_edit",
     *     methods={"GET", "PUT"}
     * )
     */
    public function edit(Request $request, Photo $photo ): Response
    {
        $form = $this->createForm(PhotoType::class, $photo, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->filesystem->remove(
                $this->getParameter('photos_directory').'/'.$photo->getFilename()
            );
            $photoFilename = $this->fileUp->upload(
                $form->get('file')->getData()
            );

            $photo->setFilename($photoFilename);

            $this->photoRepository->save($photo);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('film_view', ['id' => $photo->getFilms()->getId()]);
        }

        return $this->render(
            'photo/edit.html.twig',
            [
                'form' => $form->createView(),
                'photo' => $photo,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Photo                         $photo      Tag entity
     * @param \App\Repository\PhotoRepository           $repository Tag repository
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
     *     name="photo_delete",
     * )
     */
    public function delete(Request $request, Photo $photo, PhotoRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $photo, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($photo);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('films_index');
        }

        return $this->render(
            'photo/delete.html.twig',
            [
                'form' => $form->createView(),
                'photo' => $photo,
            ]
        );
    }
}
