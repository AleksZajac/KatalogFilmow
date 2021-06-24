<?php
/**
 * User service.
 */

namespace App\Service;


use App\Entity\User;

use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UsersService.
 */
class UserService
{
    /**
     * User repository.
     *
     * @var \App\Repository\UserRepository
     */
    private $userRepository;
    /**
     * UserService constructor.
     *
     * @param \App\Repository\UserRepository      $userRepository Users repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator          Paginator
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
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
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
     * @param \App\Entity\User $user User entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * Delete category.
     *
     * @param \App\Entity\Films $user Category entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }

}