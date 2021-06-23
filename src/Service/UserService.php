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
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
}