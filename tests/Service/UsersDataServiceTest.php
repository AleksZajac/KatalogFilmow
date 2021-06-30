<?php
/**
 * UsersDataService tests.
 */

namespace App\Tests\Service;

use App\Entity\Avatar;
use App\Entity\Films;
use App\Entity\User;
use App\Entity\UsersProfile;
use App\Repository\AvatarRepository;
use App\Repository\UserRepository;
use App\Repository\UsersProfileRepository;
use App\Service\UserService;
use App\Service\UsersProfileService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UserServiceTest.
 */
class UsersDataServiceTest extends KernelTestCase
{
    /**
     * Films service.
     *
     * @var UsersProfileRepository|object|null
     */
    private ?UsersProfileRepository $profileRepository;

    /**
     * User repository.
     *
     * @var UsersProfileService|object|null
     */
    private ?UsersProfileService $profileService;

    /**
     * User repository.
     *
     * @var UserRepository|object|null
     */
    private ?UserRepository $userRepository;
    /**
     * User repository.
     *
     * @var AvatarRepository|object|null
     */
    private ?AvatarRepository $avatarRepository;
    /**
     * Set up test.
     */

    
    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;
        $this->profileService = $container->get(UsersProfileService::class);
        $this->profileRepository = $container->get(UsersProfileRepository::class);
        $this->userRepository = $container->get(UserRepository::class);
        $this->avatarRepository = $container->get(AvatarRepository::class);
    }
}