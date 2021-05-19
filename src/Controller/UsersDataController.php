<?php
/**
 * UserDataController Controller
 */
namespace App\Controller;


use App\Form\UsersDataType;
use App\Entity\UsersData;
use App\Entity\User;
use App\Form\UsersProfileType;
use App\Repository\UserRepository;
use App\Repository\UsersDataRepository;
use App\Repository\UsersProfileRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
/**
 * Class UsersDataController
 *@Route("/profil",)
 *
 */
class UsersDataController extends AbstractController{
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
     * Edit Data
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
            $repository->save($usersprofile);
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
}