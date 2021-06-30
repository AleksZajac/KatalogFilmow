<?php
/**
 * Category Controller test.
 */

namespace App\Tests\Controller;

use App\Entity\Comments;
use App\Entity\Films;
use App\Entity\User;
use App\Entity\UsersProfile;
use App\Repository\CategoryRepository;
use App\Repository\CommentsRepository;
use App\Repository\FilmsRepository;
use App\Repository\UserRepository;
use App\Repository\UsersProfileRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class CategoryControllerTest.
 */
class FilmsControllerTest extends WebTestCase
{
    /**
     * Test client.
     */
    private KernelBrowser $httpClient;

    /**
     * Set up tests.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test index route for anonymous user.
     */
    public function testIndexRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', '/films/');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for anonymous user.
     */
    public function testIndexRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $admin = $this->createUser(['ROLE_ADMIN', 'ROLE_USER']);
        $this->logIn($admin);
        // when
        $this->httpClient->request('GET', '/films/');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    public function testShowFilm(): void
    {
        // given
        $expectedStatusCode = 200;
        $expectedFilm = $this->createFilm();
        $id = $expectedFilm->getId();
        // when
        $this->httpClient->request('GET', '/films/'.$id);
        $result = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $result);
    }

    /**
     * category.
     */
    private function createCategory()
    {
        $category = new \App\Entity\Category();
        $category->setName('test');
        $categoryRepository = self::$container->get(CategoryRepository::class);
        $categoryRepository->save($category);

        return $category;
    }

    /**
     * Test index route for anonymous user.
     */
    public function testIndexRouteSearch(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $aa = $this->httpClient->request('GET', '/films/');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $aa->filter('form')->form();
        $form['search']->setValue('query');
        $this->httpClient->submit($form);

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Simulate user log in.
     *
     * @param User $user User entity
     */
    private function logIn(User $user): void
    {
        $session = self::$container->get('session');

        $firewallName = 'main';
        $firewallContext = 'main';

        $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->httpClient->getCookieJar()->set($cookie);
    }

    /**
     * Create film.
     */
    private function createFilm(): Films
    {
        $film = new Films();
        $film->setTitle('Title TEst');
        $film->setDescription('fff');
        $film->setReleaseDate('2021-07-15');
        $film->setDescription('ssa');
        $film->setCategory($this->createCategory());

        $filmrepo = self::$container->get(FilmsRepository::class);
        $filmrepo->save($film);

        return $film;
    }

    /**
     * Test create film for admin user.
     */
    public function testCreateFilmAdminUser(): void
    {
        // given
        $expectedStatusCode = 301;
        $admin = $this->createUser(['ROLE_ADMIN', 'ROLE_USER']);
        $this->logIn($admin);
        // when
        $this->httpClient->request('GET', '/films/new/');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test create film for admin user.
     */
    public function testCreateFilmNonAdmin(): void
    {
        // given
        $expectedStatusCode = 301;
        $admin = $this->createUser([User::ROLE_USER]);
        $this->logIn($admin);
        // when
        $this->httpClient->request('GET', '/films/new/');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Create user.
     *
     * @param array $roles User roles
     *
     * @return User User entity
     */
    private function createUser(array $roles): User
    {
        $passwordEncoder = self::$container->get('security.password_encoder');
        $user = new User();
        $user->setEmail('user568000001@example.com');
        $user->setRoles($roles);
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                'p@5687de5w0rd'
            )
        );
        $user->setUsersprofile($this->createUserProfile());
        $userRepository = self::$container->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
    }

    private function createComment($film, $userprofile)
    {
        $profile = new Comments();
        $profile->setContent('gd');
        $profile->setLogin($userprofile);
        $profile->setFilms($film);
        $profileRepository = self::$container->get(CommentsRepository::class);
        $profileRepository->save($profile);

        return $profile;
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
}
