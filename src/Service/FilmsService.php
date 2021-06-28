<?php
/**
 * Category service.
 */

namespace App\Service;

use App\Entity\Films;
use App\Repository\FilmsRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
     * @var CategoryService
     */
    private $categoryService;

    /**
     * Tag service.
     *
     * @var TagService
     */
    private $tagService;
    /**
     * Category repository.
     *
     * @var FilmsRepository
     */
    private $filmsRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * FilmsService constructor.
     *
     * @param FilmsRepository $filmsRepository Category repository
     * @param PaginatorInterface $paginator       Paginator
     * @param CategoryService $categoryService Category service
     * @param TagService $tagService      Tag service
     */
    public function __construct(FilmsRepository $filmsRepository, PaginatorInterface $paginator, CategoryService $categoryService, TagService $tagService)
    {
        $this->filmsRepository = $filmsRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Create paginated list.
     *
     * @param int   $page    Page number
     * @param array $filters Filters array
     *
     * @return PaginationInterface Paginated list
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
     * @param Films $films Category entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Films $films): void
    {
        $this->filmsRepository->save($films);
    }

    /**
     * Delete category.
     *
     * @param Films $films Category entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Films $films): void
    {
        $this->filmsRepository->delete($films);
    }
    /**
     * Show user profile.
     *
     * @param int $id Id user
     *
     * @return Films user
     */
    public function showFilms(int $id)
    {
        return $this->filmsRepository->find($id);
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
}
