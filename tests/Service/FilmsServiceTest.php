<?php
/**
 * FilmsService tests.
 */

namespace App\Tests\Service;

use App\Entity\Films;
use App\Repository\CategoryRepository;
use App\Repository\FilmsRepository;
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
     * category
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
     * Set up test.
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;
        $this->categoryRepository = $container->get(CategoryRepository::class);
        $this->filmsService = $container->get(FilmsService::class);
        $this->filmsRepository = $container->get(FilmsRepository::class);
    }

}
