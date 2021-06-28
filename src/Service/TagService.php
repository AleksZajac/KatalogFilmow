<?php
/**
 * Tag service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Tag;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TagService.
 */
class TagService
{
    /**
     * Tag repository.
     *
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * CategoryService constructor.
     *
     * @param CategoryRepository      $tagRepository Tag repository
     * @param PaginatorInterface $paginator     Paginator
     */
    public function __construct(TagRepository $tagRepository, PaginatorInterface $paginator)
    {
        $this->tagRepository = $tagRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->tagRepository->queryAll(),
            $page,
            TagRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save category.
     *
     * @param Tag $tag Category entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Tag $tag): void
    {
        $this->tagRepository->save($tag);
    }

    /**
     * Delete category.
     *
     * @param Tag $tag Category entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Tag $tag): void
    {
        $this->tagRepository->delete($tag);
    }

    /**
     * Find by title.
     *
     * @param string $name tag title
     *
     * @return Tag|null Tag entity
     */
    public function findOneByName(string $name): ?Tag
    {
        return $this->tagRepository->findOneByName($name);
    }

    /**
     * Find tag by Id.
     *
     * @param int $id Tag Id
     *
     * @return Tag|null Tag entity
     */
    public function findOneById(int $id): ?Tag
    {
        return $this->tagRepository->findOneById($id);
    }

    /**
     * @return Category[]|Tag[]
     */
    public function allTag()
    {
        return $this->tagRepository->findAll();
    }
}
