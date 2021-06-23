<?php
/**
 * Tag service.
 */

namespace App\Service;

use App\Entity\FavoriteMovies;
use App\Repository\FavoriteMoviesRepository;
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
     * @var \App\Repository\FavoriteMoviesRepository
     */
    private $favoriteMoviesRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * CategoryService constructor.
     *
     * @param \App\Repository\FavoriteMoviesRepository      $favoriteMoviesRepository Tag repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator          Paginator
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
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
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
     * @param \App\Entity\FavoriteMovies $favorite Category entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(FavoriteMovies $favorite): void
    {
        $this->favoriteMoviesRepository->save($favorite);
    }

    /**
     * Delete category.
     *
     * @param \App\Entity\FavoriteMovies $favorite Favorite entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(FavoriteMovies $favorite): void
    {
        $this->favoriteMoviesRepository->delete($favorite);
    }
    /**
     * Delete category.
     *
     * @param \App\Entity\FavoriteMovies $favorite Favorite entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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
     * @return \App\Entity\FavoriteMovies|null Tag entity
     */
    public function findOneById(int $id): ?FavoriteMovies
    {
        return $this->favoriteMoviesRepository->findOneById($id);
    }

}