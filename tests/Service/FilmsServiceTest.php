<?php
/**
 * FilmsService tests.
 */

namespace App\Tests\Service;

use App\Entity\Films;
use App\Entity\Photo;
use App\Repository\CategoryRepository;
use App\Repository\FilmsRepository;
use App\Repository\PhotoRepository;
use App\Service\FilmsService;
use Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class FilmServiceTest.
 */
class FilmsServiceTest extends KernelTestCase
{
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
     * Test save.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testSave(): void
    {
        // given
        $expectedFilm = new Films();
        $expectedFilm->setTitle('Test film');
        $expectedFilm->setReleaseDate('2021-07-15');
        $expectedFilm->setDescription('ssa');
        $expectedFilm->setCategory($this->createCategory());

        // when
        $this->filmsService->save($expectedFilm);
        $resultFilm = $this->filmsRepository->findOneById(
            $expectedFilm->getId()
        );

        // then
        $this->assertEquals($expectedFilm, $resultFilm);
    }

    /**
     * category.
     */
    private function createCategory()
    {
        $category = new \App\Entity\Category();
        $category->setName('test');
        $categoryRepository = self::$container->get(CategoryRepository::class);
        $categoryRepository->save($category);

        return $category;
    }

    /**
     * Test delete.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testDelete(): void
    {
        // given
        $expectedFilm = new Films();
        $expectedFilm->setTitle('Test film');
        $expectedFilm->setReleaseDate('2021-07-15');
        $expectedFilm->setDescription('ssa');
        $expectedFilm->setCategory($this->createCategory());
        $this->filmsService->save($expectedFilm);
        $expectedId = $expectedFilm->getId();

        // when
        $this->filmsService->delete($expectedFilm);
        $result = $this->filmsRepository->findOneById($expectedId);

        // then
        $this->assertNull($result);
    }

    /**
     * Test find by id.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testFindById(): void
    {
        // given
        $expectedFilm = new Films();
        $expectedFilm->setTitle('Test film');
        $expectedFilm->setReleaseDate('2021-07-15');
        $expectedFilm->setDescription('ssa');
        $expectedFilm->setCategory($this->createCategory());
        $this->filmsService->save($expectedFilm);

        // when
        $result = $this->filmsService->showFilms($expectedFilm->getId());

        // then
        $this->assertEquals($expectedFilm->getId(), $result->getId());
    }

    /**
     * Test pagination  list.
     */
    public function testCreatePaginatedListList(): void
    {
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 10;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $film = new Films();
            $film->setReleaseDate('2021-07-15');
            $film->setDescription('ssa');
            $film->setCategory($this->createCategory());
            $film->setTitle('Test film #'.$counter);
            $this->filmsRepository->save($film);

            ++$counter;
        }

        // when
        $result = $this->filmsService->createPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Test pagination  list - filtry ctaegory.
     */
    public function testCreatePaginatedListFilCategory(): void
    {
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 1;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $film = new Films();
            $category = new \App\Entity\Category();
            $category->setName('test1');
            $categoryRepository = self::$container->get(CategoryRepository::class);
            $categoryRepository->save($category);
            $film->setReleaseDate('2021-07-15');
            $film->setDescription('ssa');
            $film->setCategory($category);
            $film->setTitle('Test film #'.$counter);
            $this->filmsRepository->save($film);

            ++$counter;
        }

        $fil = [];
        $fil['category_id'] = $category->getId();
        // when
        $result = $this->filmsService->createPaginatedList($page, $fil);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    public function testSavePhoto(): void
    {
        $film = new Films();
        $category = new \App\Entity\Category();
        $category->setName('test2');
        $categoryRepository = self::$container->get(CategoryRepository::class);
        $categoryRepository->save($category);
        $film->setReleaseDate('2021-07-15');
        $film->setDescription('ssa');
        $film->setCategory($category);
        $film->setTitle('Test film 2');
        $this->filmsRepository->save($film);
        $photo = new Photo();
        $photo->setFilms($film);
        $photo->setFilename('zdj1255.jpg');
        $this->photoRepository->save($photo);
        $expectedId = $photo->getId();

        $result = $this->photoRepository->findOneBy(['id'=> $expectedId]);

        // then
        $this->assertEquals($expectedId, $result->getId());

    }


    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;
        $this->categoryRepository = $container->get(CategoryRepository::class);
        $this->filmsService = $container->get(FilmsService::class);
        $this->filmsRepository = $container->get(FilmsRepository::class);
        $this->photoRepository = $container->get(PhotoRepository::class);
    }
}
