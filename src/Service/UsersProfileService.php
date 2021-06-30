<?php
/**
 * Category service.
 */

namespace App\Service;

use App\Entity\Category;
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
     * @param CategoryRepository $categoryRepository Category repository
     * @param PaginatorInterface $paginator          Paginator
     */
    public function __construct(UsersProfileRepository $profileRepository, PaginatorInterface $paginator)
    {
        $this->profileRepository = $profileRepository;
        $this->paginator = $paginator;
    }

    public function save(UsersProfileRepository $profile): void
    {
        $this->profileRepository->save(($profile));
    }
}