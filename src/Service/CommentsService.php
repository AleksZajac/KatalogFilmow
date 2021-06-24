<?php
/**
 * Categories service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Comments;
use App\Repository\CommentsRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CategoryService.
 */
class CommentsService
{
    /**
     * Category repository.
     *
     * @var \App\Repository\CommentsRepository
     */
    private $commentsRepository;
    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * Categories constructor.
     *
     * @param \Knp\Component\Pager\PaginatorInterface $paginator          Paginator
     * @param \App\Repository\CommentsRepository      $commentsRepository Categories Repository
     */
    public function __construct(CommentsRepository $commentsRepository, PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
        $this->commentsRepository = $commentsRepository;
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
            $this->commentsRepository->findAll(),
            $page,
            CommentsRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Delete category.
     *
     * @param Comments $comments Categories entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Comments $comments): void
    {
        $this->commentsRepository->delete($comments);
    }

    /**
     * Save announcement.
     *
     * @param Comments $comments Announcement entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Comments $comments): void
    {
        $this->commentsRepository->save($comments);
    }
}
