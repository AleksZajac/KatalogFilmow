<?php
/**
 * TagService tests.
 */

namespace App\Tests\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Repository\FilmsRepository;
use App\Service\FilmsService;

use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class TagServiceTest.
 */
class TagServiceTest extends KernelTestCase
{
    /**
     * Films service.
     *
     * @var TagService|object|null
     */
    private ?TagService $tagService;

    /**
     * Film repository.
     *
     * @var FilmsRepository|object|null
     */
    private ?FilmsRepository $filmsRepository;

    /**
     * Tag repository.
     *
     * @var TagRepository|object|null
     */
    private ?TagRepository $tagRepository;

    /**
     * Test save.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testSave(): void
    {
        // given
        $expectedTag = new Tag();
        $expectedTag->setName('Test tag');


        // when
        $this->tagService->save($expectedTag);
        $resultTag = $this->tagRepository->findOneById(
            $expectedTag->getId()
        );

        // then
        $this->assertEquals($expectedTag, $resultTag);
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
        $expectedTag = new Tag();
        $expectedTag->setName('Test tag');

        $this->tagService->save($expectedTag);
        $expectedId = $expectedTag->getId();

        // when
        $this->tagService->delete($expectedTag);
        $result = $this->tagRepository->findOneById($expectedId);

        // then
        $this->assertNull($result);
    }

    /**
     * Test find by id.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testFindByName(): void
    {
        // given
        $expectedTag = new Tag();
        $expectedTag->setName('test');

        $this->tagService->save($expectedTag);

        // when
        $result = $this->tagService->findOneByName($expectedTag->getName());

        // then
        $this->assertEquals($expectedTag->getName(), $result->getName());
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
        $expectedTag = new Tag();
        $expectedTag->setName('test');

        $this->tagService->save($expectedTag);

        // when
        $result = $this->tagService->findOneById($expectedTag->getId());

        // then
        $this->assertEquals($expectedTag->getId(), $result->getId());
    }
// FIND BY NAME!!!

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
            $tag = new Tag();
            $tag->setName('test');
            $this->tagRepository->save($tag);


            ++$counter;
        }

        // when
        $result = $this->tagService->createPaginatedList($page);

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
        $this->tagRepository = $container->get(TagRepository::class);
        $this->tagService = $container->get(TagService::class);
        $this->filmsRepository = $container->get(FilmsRepository::class);
    }

    /**
     * category
     */
    private function createTag()
    {
        $tag = new \App\Entity\Tag();
        $tag->setName('test');
        $tagRepository = self::$container->get(TagRepository::class);
        $tagRepository->save($tag);

        return $tag;
    }

}
