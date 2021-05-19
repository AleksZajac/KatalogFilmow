<?php
/**
 * Category service.
 */

namespace App\Service;

use App\Entity\Films;
use App\Repository\FilmsRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CategoryService.
 */
class FilmsService
{
    /**
     * Category service.
     *
     * @var \App\Service\CategoryService
     */
    private $categoryService;

    /**
     * Tag service.
     *
     * @var \App\Service\TagService
     */
    private $tagService;
    /**
     * Category repository.
     *
     * @var \App\Repository\FilmsRepository
     */
    private $filmsRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * FilmsService constructor.
     *
     * @param \App\Repository\FilmsRepository         $filmsRepository Category repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator       Paginator
     * @param \App\Service\CategoryService            $categoryService Category service
     * @param \App\Service\TagService                 $tagService      Tag service
     */
    public function __construct(FilmsRepository $filmsRepository, PaginatorInterface $paginator, CategoryService $categoryService, TagService $tagService)
    {
        $this->filmsRepository = $filmsRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }
    /**
     * Prepare filters for the tasks list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['category_id']) && is_numeric($filters['category_id'])) {
            $category = $this->categoryService->findOneById(
                $filters['category_id']
            );
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        if (isset($filters['tag_id']) && is_numeric($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        return $resultFilters;
    }
    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *@param array                                               $filters Filters array
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);
        return $this->paginator->paginate(
            $this->filmsRepository->queryAll($filters),
            $page,
            FilmsRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save category.
     *
     * @param \App\Entity\Films $films Category entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Films $films): void
    {
        $this->filmsRepository->save($films);
    }

    /**
     * Delete category.
     *
     * @param \App\Entity\Films $films Category entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Films $films): void
    {
        $this->filmsRepository->delete($films);
    }
}
