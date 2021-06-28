<?php
/**
 * User service.
 */

namespace App\Service;

use App\Entity\Films;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UsersService.
 */
class UserService
{
    /**
     * User repository.
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository Users repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(UserRepository $userRepository, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
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
            $this->userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Show user profile.
     *
     * @param int $id Id user
     *
     * @return User user
     */
    public function showUser(int $id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * Save category.
     *
     * @param User $user User entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * Delete category.
     *
     * @param Films $user Category entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }
}
