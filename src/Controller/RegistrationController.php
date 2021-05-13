<?php
/**
 * Registration Controller
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\UsersProfile;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegistrationController
 * @Route("/register")
 */
class RegistrationController extends AbstractController
{
    /**
     * Regiaster action  - symfony documents!!!
     * @param \Symfony\Component\HttpFoundation\Request $request         HTTP request
     * @param UserPasswordEncoderInterface              $passwordEncoder
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route("/",
     *     name="registration",
     *       methods={"GET", "POST"},
     * )
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $name = new UsersProfile();
        $user->getUsersprofile()->add($name);
        $form = $this->createForm(UserType::class, $user);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $user->setUsersprofile($name);
            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            $this->addFlash('success', 'message.register_successfully');

            return $this->redirectToRoute('security_login');
        }

        return $this->render(
            'registration/register.html.twig',
            ['form' => $form->createView(),
                'show_legend' => false, ]
        );
    }
}
