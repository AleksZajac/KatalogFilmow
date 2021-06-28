<?php
/**
 * Tag service.
 */

namespace App\Service;

use App\Entity\FavoriteMovies;
use App\Repository\FavoriteMoviesRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class FavoriteService.
 */
class FavoriteService
{
    /**
     * Tag repository.
     *
     * @var FavoriteMoviesRepository
     */
    private $favoriteMoviesRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * CategoryService constructor.
     *
     * @param FavoriteMoviesRepository $favoriteMoviesRepository Tag repository
     * @param PaginatorInterface $paginator                Paginator
     */
    public function __construct(FavoriteMoviesRepository $favoriteMoviesRepository, PaginatorInterface $paginator)
    {
        $this->favoriteMoviesRepository = $favoriteMoviesRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     * @param int $id
     *
     * @return PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page, int $id): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->favoriteMoviesRepository->queryAll($id),
            $page,
            FavoriteMoviesRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save category.
     *
     * @param FavoriteMovies $favorite Category entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(FavoriteMovies $favorite): void
    {
        $this->favoriteMoviesRepository->save($favorite);
    }

    /**
     * Delete category.
     *
     * @param FavoriteMovies $favorite Favorite entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(FavoriteMovies $favorite): void
    {
        $this->favoriteMoviesRepository->delete($favorite);
    }

    /**
     * Delete category.
     *
     * @param FavoriteMovies $favorite Favorite entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deletefilms(FavoriteMovies $favorite): void
    {
        $this->favoriteMoviesRepository->delete($favorite);
    }

    /**
     * Find tag by Id.
     *
     * @param int $id Tag Id
     *
     * @return FavoriteMovies|null Tag entity
     */
    public function findOneById(int $id): ?FavoriteMovies
    {
        return $this->favoriteMoviesRepository->findOneById($id);
    }
}
