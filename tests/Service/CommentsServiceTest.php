<?php
/**
 * CommentsService tests.
 */

namespace App\Tests\Service;

use App\Entity\Comments;
use App\Entity\Films;
use App\Entity\User;
use App\Entity\UsersProfile;
use App\Repository\CategoryRepository;
use App\Repository\CommentsRepository;
use App\Repository\FilmsRepository;
use App\Repository\UserRepository;
use App\Repository\UsersProfileRepository;
use App\Service\CommentsService;
use App\Service\FilmsService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CommentsServiceTest.
 */
class CommentsServiceTest extends KernelTestCase
{
    /**
     * Film repository.
     *
     * @var FilmsRepository|object|null
     */
    private ?FilmsRepository $filmsRepository;

    /**
     * Film repository.
     *
     * @var FilmsService|object|null
     */
    private ?FilmsService $filmsService;
    /**
     * Comments repository.
     *
     * @var CommentsService|object|null
     */
    private ?CommentsService $commentsService;
    /**
     * Comments repository.
     *
     * @var CommentsRepository|object|null
     */
    private ?CommentsRepository $commentsRepository;

    /**
     * @var UserRepository|object|null
     */
    private ?UserRepository  $userRepository;

    /**
     * Test pagination  list .
     */
    public function testCreatePaginatedList(): void
    {
        // given
        $page = 1;
        $dataSetSize = 10;
        $expectedResultSize = 10;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $film = new Films();
            $category = new \App\Entity\Category();
            $category->setName('test3'.$counter);
            $categoryRepository = self::$container->get(CategoryRepository::class);
            $categoryRepository->save($category);
            $film->setReleaseDate('2021-07-15');
            $film->setDescription('ssa');
            $film->setCategory($category);
            $film->setTitle('Test film #'.$counter);
            $this->filmsRepository->save($film);

            $user = new User();
            $user->setEmail('test'.$counter.'@gmail.com');
            $user->setPassword('123445');
            $user->setUsersprofile($this->createUserProfile());
            $userprofile = $user->getUsersprofile();
            $comment = new Comments();
            $comment->setLogin($userprofile);
            $comment->setFilms($film);
            $comment->setContent(' Content#2'.$counter);

            $this->commentsRepository->save($comment);

            ++$counter;
        }

        // when
        $result = $this->commentsService->createPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Test save.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testSave(): void
    {
        // given
        $film = new Films();
        $category = new \App\Entity\Category();
        $category->setName('test3');
        $categoryRepository = self::$container->get(CategoryRepository::class);
        $categoryRepository->save($category);
        $film->setReleaseDate('2021-07-15');
        $film->setDescription('ssa');
        $film->setCategory($category);
        $film->setTitle('Test film #p');
        $this->filmsRepository->save($film);

        $user = new User();
        $user->setEmail('test89@gmail.com');
        $user->setPassword('123445');
        $user->setUsersprofile($this->createUserProfile());
        $userprofile = $user->getUsersprofile();
        $comment = new Comments();
        $comment->setLogin($userprofile);
        $comment->setFilms($film);
        $comment->setContent(' Content#2');

        // when
        $this->commentsService->save($comment);
        $resultComments = $this->commentsRepository->findOneById(
            $comment->getId()
        );

        // then
        $this->assertEquals($comment, $resultComments);
    }
    /**
     * Test save.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testDeleete(): void
    {
        // given
        $film = new Films();
        $category = new \App\Entity\Category();
        $category->setName('test57uf3');
        $categoryRepository = self::$container->get(CategoryRepository::class);
        $categoryRepository->save($category);
        $film->setReleaseDate('2021-07-15');
        $film->setDescription('ssdsa');
        $film->setCategory($category);
        $film->setTitle('Test film #p');
        $this->filmsRepository->save($film);

        $user = new User();
        $user->setEmail('test8e9@gmail.com');
        $user->setPassword('123445');
        $user->setUsersprofile($this->createUserProfile());
        $userprofile = $user->getUsersprofile();

        $comment = new Comments();
        $comment->setLogin($userprofile);
        $comment->setFilms($film);
        $comment->setContent(' Content#2');

        $this->commentsService->save($comment);
        $expectedId = $comment->getId();
        // when
        $this->commentsService->delete($comment);
        $resultComments = $this->commentsRepository->findOneById(
            $expectedId
        );

        // then
        $this->assertNull($resultComments);
    }

    private function createUserProfile()
    {
        $profile = new UsersProfile();
        $profile->setName('Testowy8');
        $profile->setSurname('Testowenazwiskod');
        $profile->setLogin('jestem_testem');
        $profileRepository = self::$container->get(UsersProfileRepository::class);
        $profileRepository->save($profile);

        return $profile;
    }
    private function createCategory()
    {
        $category = new \App\Entity\Category();
        $category->setName('test');
        $categoryRepository = self::$container->get(CategoryRepository::class);
        $categoryRepository->save($category);

        return $category;
    }

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;
        $this->userRepository = $container->get(UserRepository::class);
        $this->commentsRepository = $container->get(CommentsRepository::class);
        $this->commentsService = $container->get(CommentsService::class);
        $this->filmsService = $container->get(FilmsService::class);
        $this->filmsRepository = $container->get(FilmsRepository::class);
        $this->userRepository = $container->get(UserRepository::class);
    }
}
