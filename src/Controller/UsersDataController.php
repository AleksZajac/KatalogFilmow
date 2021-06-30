<?php
/**
 * UserDataController Controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangeUserPasswordType;
use App\Form\Model\ChangePassword;
use App\Form\UsersProfileType;
use App\Repository\UserRepository;
use App\Repository\UsersProfileRepository;
use App\Service\UsersProfileService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class UsersDataController.
 *
 *@Route("/profil",)
 */
class UsersDataController extends AbstractController
{
    /**
     * Category service.
     *
     * @var UsersProfileService
     */
    private $profileService;

    /**
     * CategoryController constructor.
     *
     * @param UsersProfileService $profileService Tag service
     */
    public function __construct(UsersProfileService $profileService)
    {
        $this->profileService = $profileService;
    }
    /**
     * Index action.
     *
     * @Route(
     *     "/",
     *     name="profil_index",
     *     )
     *
     * @return Response HTTP response
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED",
     *      message="You can not enty")
     */
    public function index(): Response
    {
        $usersdata = $this->getUser()->getUSersProfile();

        return $this->render(
            'usersdata/index.html.twig',
            ['usersdata' => $usersdata]
        );
    }

    /**
     * Edit Data.
     *
     * @param UsersProfileRepository $repository UsersProfile repository
     * @param Request                $request    HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/editData",
     *     methods={"GET", "PUT"},
     *     name="data_edit",
     * )
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED",
     *      message="You can not enty")
     */
    public function editData(UsersProfileRepository $repository, Request $request): Response
    {
        $usersprofile = $this->getUser()->getUsersprofile();
        $form = $this->createForm(UsersProfileType::class, $usersprofile, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->profileService->save($usersprofile);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('profil_index');
        }

        return $this->render(
            'usersdata/editData.html.twig',
            [
                'form' => $form->createView(),
                'usersprofile' => $usersprofile,
            ]
        );
    }

    /**
     * Change password action.
     *
     * @param Request                      $request         HTTP request
     * @param UserRepository               $repository      User repository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Security                     $security
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *      "/change_pwd",
     *      name="change_password",
     *      methods={"GET", "PUT"},
     * )
     *
     * @IsGranted(
     *     "IS_AUTHENTICATED_REMEMBERED",
     *     message="You can not edit"
     * )
     */
    public function changeUserPassword(Request $request, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder, Security $security)
    {
        $user = $security->getUser();
        $changepwd = new ChangePassword();
        $form = $this->createForm(ChangeUserPasswordType::class, $changepwd, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $changepwd->getPassword());
            $user = $this->getUser();
            $user->setPassword($password);
            $repository->save($user);
            $this->addFlash('success', 'success.changepassword');

            return $this->redirectToRoute('profil_index');
        }

        return $this->render(
            'usersdata/change_pwd.html.twig',
            ['form' => $form->createView()]
        );
    }
}
