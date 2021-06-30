<?php
/**
 * FavoriteServiceTest tests.
 */

namespace App\Tests\Service;

use App\Entity\Films;
use App\Repository\CategoryRepository;
use App\Repository\FavoriteMoviesRepository;
use App\Repository\FilmsRepository;
use App\Repository\PhotoRepository;
use App\Service\FavoriteService;
use App\Service\FilmsService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class FavoriteServiceTest.
 */
class FavoriteServiceTest extends KernelTestCase
{
    /**
     * FavoriteMovies service.
     *
     * @var FavoriteService|object|null
     */
    private ?FavoriteService $favoriteService;

    /**
     * FavoriteMoviesRepository.
     *
     * @var FavoriteMoviesRepository|object|null
     */
    private ?FavoriteMoviesRepository $favoriteMoviesRepository;
    /**
     * Films service.
     *
     * @var FilmsService|object|null
     */
    private ?FilmsService $filmsService;

    /**
     * Film repository.
     *
     * @var FilmsRepository|object|null
     */
    private ?FilmsRepository $filmsRepository;

    /**
     * Film repository.
     *
     * @var PhotoRepository|object|null
     */
    private ?PhotoRepository $photoRepository;
    /**
     * Category repository.
     *
     * @var CategoryRepository|object|null
     */
    private ?CategoryRepository $categoryRepository;



    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;
        $this->favoriteMoviesRepository = $container->get(FavoriteMoviesRepository::class);
        $this->favoriteService = $container->get(FavoriteService::class);
        $this->categoryRepository = $container->get(CategoryRepository::class);
        $this->filmsService = $container->get(FilmsService::class);
        $this->filmsRepository = $container->get(FilmsRepository::class);
        $this->photoRepository = $container->get(PhotoRepository::class);
    }
}
