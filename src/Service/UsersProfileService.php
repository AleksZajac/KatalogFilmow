<?php
/**
 * Category service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\UsersProfile;
use App\Repository\CategoryRepository;
use App\Repository\UsersProfileRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CategoryService.
 */
class UsersProfileService
{
    /**
     * sersProfileRepository repository.
     *
     * @var UsersProfileRepository
     */
    private $profileRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * CategoryService constructor.
     *
     * @param UsersProfileRepository $profileRepository UsersProfile repository
     * @param PaginatorInterface     $paginator         Paginator
     */
    public function __construct(UsersProfileRepository $profileRepository, PaginatorInterface $paginator)
    {
        $this->profileRepository = $profileRepository;
        $this->paginator = $paginator;
    }

    /**
     * @param UsersProfile $profile
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(UsersProfile $profile): void
    {
        $this->profileRepository->save($profile);
    }
}
