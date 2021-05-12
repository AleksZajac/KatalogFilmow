<?php
/**
 * UserDataController Controller
 */
namespace App\Controller;


use App\Form\UsersDataType;
use App\Entity\UsersData;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\UsersDataRepository;
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
        $usersdata = $this->getUser()->getUsersData();

        return $this->render(
            'usersdata/index.html.twig',
            ['usersdata' => $usersdata]
        );
    }
}