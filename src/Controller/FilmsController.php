<?php
/**
 * Films controller.
 */

namespace App\Controller;

use App\Repository\FilmsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param \App\Repository\FilmsRepository $repository Films repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="films_index",
     * )
     */
    public function index(FilmsRepository $repository): Response
    {
        return $this->render(
            'films/index.html.twig',
            ['data' => $repository->findAll()]
        );
    }
    /**
     * Show action.
     *
     * @param \App\Repository\FilmsRepository $repository Record repository
     * @param int                              $id         Record id
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="film_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     */
    public function show(FilmsRepository $repository, int $id): Response
    {
        return $this->render(
            'film/show.html.twig',
            ['item' => $repository->findById($id)]
        );
    }
}