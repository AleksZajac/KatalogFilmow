<?php
/**
 * CategoryService tests.
 */

namespace App\Tests\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\FilmsRepository;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CategoryServiceTest.
 */
class CategoryServiceTest extends KernelTestCase
{
    /**
     * Category service.
     *
     * @var CategoryService|object|null
     */
    private ?CategoryService $categoryService;

    /**
     * Category repository.
     *
     * @var CategoryRepository|object|null
     */
    private ?CategoryRepository $categoryRepository;

    /**
     * Film repository.
     *
     * @var FilmsRepository|object|null
     */
    private ?FilmsRepository $filmsRepository;

    /**
     * Test save.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testSave(): void
    {
        // given
        $expectedCategory = new Category();
        $expectedCategory->setName('Test Category');

        // when
        $this->categoryService->save($expectedCategory);
        $resultCategory = $this->categoryRepository->findOneById(
            $expectedCategory->getId()
        );

        // then
        $this->assertEquals($expectedCategory, $resultCategory);
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
        $expectedCategory = new Category();
        $expectedCategory->setName('Test Category');
        $this->categoryRepository->save($expectedCategory);
        $expectedId = $expectedCategory->getId();

        // when
        $this->categoryService->delete($expectedCategory);
        $result = $this->categoryRepository->findOneById($expectedId);

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
        $expectedCategory = new Category();
        $expectedCategory->setName('Test Category');
        $this->categoryRepository->save($expectedCategory);

        // when
        $result = $this->categoryService->findOneById($expectedCategory->getId());

        // then
        $this->assertEquals($expectedCategory->getId(), $result->getId());
    }

    /**
     * Test pagination  list.
     */
    public function testCreatePaginatedListList(): void
    {
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $category = new Category();
            $category->setName('Test Category #' . $counter);
            $this->categoryRepository->save($category);

            ++$counter;
        }

        // when
        $result = $this->categoryService->createPaginatedList($page);

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
        $this->categoryService = $container->get(CategoryService::class);
        $this->filmsRepository = $container->get(FilmsRepository::class);
    }

    // other tests for paginated list
}
