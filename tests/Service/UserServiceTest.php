<?php
/**
 * UserService tests.
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
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UserServiceTest.
 */
class UserServiceTest extends KernelTestCase
{
    /**
     * Films service.
     *
     * @var UserService|object|null
     */
    private ?UserService $userService;

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
     * User repository.
     *
     * @var UsersProfileRepository|object|null
     */
    private ?UsersProfileRepository $profileRepository;

    /**
     * Test save.
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testSave(): void
    {
        // given
        $expectedUser = new User();
        $expectedUser->setEmail('test@gmail.com');
        $expectedUser->setPassword('123445');

        $expectedUser->setUsersprofile($this->createUserProfile());

        // when
        $this->userService->save($expectedUser);
        $resultUser = $this->userRepository->findOneById(
            $expectedUser->getId()
        );

        // then
        $this->assertEquals($expectedUser, $resultUser);
    }

    private function createUserProfile()
    {
        $profile = new UsersProfile();
        $profile->setName('Testowy');
        $profile->setSurname('Testowenazwisko');
        $profile->setLogin('jestem_testem');
        $profileRepository = self::$container->get(UsersProfileRepository::class);
        $profileRepository->save($profile);

        return $profile;
    }

    /**
     * Test delete.
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testDelete(): void
    {
        // given
        $expectedUser = new User();
        $expectedUser->setEmail('test@gmail.com');
        $expectedUser->setPassword('123445');
        $expectedUser->setUsersprofile($this->createUserProfile());

        $this->userService->save($expectedUser);
        $expectedId = $expectedUser->getId();

        // when
        $this->userService->delete($expectedUser);
        $result = $this->userRepository->findOneById($expectedId);

        // then
        $this->assertNull($result);
    }

    /**
     * Test find by id.
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testFindById(): void
    {
        // given
        $expectedUser = new User();
        $expectedUser->setEmail('test@gmail.com');
        $expectedUser->setPassword('123445');
        $expectedUser->setUsersprofile($this->createUserProfile());
        $this->userService->save($expectedUser);

        // when
        $result = $this->userService->showUser($expectedUser->getId());

        // then
        $this->assertEquals($expectedUser->getId(), $result->getId());
    }

    /**
     * Test pagination  list.
     */
    public function testCreatePaginatedListList(): void
    {
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 5;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $expectedUser = new User();
            $expectedUser->setEmail('test@gmail.com');
            $expectedUser->setPassword('123445');
            $expectedUser->setUsersprofile($this->createUserProfile());
            $this->userService->save($expectedUser);

            ++$counter;
        }

        // when
        $result = $this->userService->createPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    public function testSaveAvatar(): void
    {
        $user = new User();
        $user->setEmail('test006@gmail.com');
        $user->setPassword('123445');
        $user->setUsersprofile($this->createUserProfile());
        $this->userService->save($user);

        $avatar = new Avatar();
        $avatar->setUser($user);
        $avatar->setFilename('avatartest125.jpg');
        $this->avatarRepository->save($avatar);
        $expectedId = $avatar->getId();

        $result = $this->avatarRepository->findOneBy(['id' => $expectedId]);

        // then
        $this->assertEquals($expectedId, $result->getId());
    }
    public function testDeleteAvatar(): void
    {
        $user = new User();
        $user->setEmail('test006@gmail.com');
        $user->setPassword('123445');
        $user->setUsersprofile($this->createUserProfile());
        $this->userService->save($user);

        $avatar = new Avatar();
        $avatar->setUser($user);
        $avatar->setFilename('avatartest122.jpg');
        $this->avatarRepository->save($avatar);
        $expectedId = $avatar->getId();
        $this->avatarRepository->delete($avatar);
        $result = $this->avatarRepository->findOneBy(['id' => $expectedId]);

        // then
        $this->assertNull($result);
    }
    public function testShowProfile(): void
    {
        $user = new User();
        $user->setEmail('test00677@gmail.com');
        $user->setPassword('123445');
        $user->setUsersprofile($this->createUserProfile());
        $this->userService->save($user);

        $result = $this->userService->showUser($user->getId());

        // then
        $this->assertEquals($user->getId(), $result->getId());
    }
    public function testAddAdmin(): void
    {
        //given
        $user = new User();
        $user->setEmail('test006770@gmail.com');
        $user->setPassword('123445');
        $user->setUsersprofile($this->createUserProfile());
        $this->userService->save($user);

        //when
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $this->userService->save($user);
        $result = $this->userService->showUser($user->getId());

        // then
        $this->assertEquals(['ROLE_USER', 'ROLE_ADMIN'], $result->getRoles());
    }

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;
        $this->userRepository = $container->get(UserRepository::class);
        $this->userService = $container->get(UserService::class);
        $this->profileRepository = $container->get(UsersProfileRepository::class);
        $this->avatarRepository = $container->get(AvatarRepository::class);
    }
}
