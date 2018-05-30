<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as Encoder;

use AppBundle\Entity\User;
use AppBundle\Entity\Role;

class UserController extends Controller
{
    /**
     * Page de création de compte
     *
     * @Route("/signin", name="signin")
     */
    public function signinAction(Request $request, Encoder $encoder)
    {
        // On intègre un formulaire en dessous des réponses pour pouvoir poster une réponse
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On hash le mot de passe de l'utilisateur
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            $em = $this->getDoctrine()->getManager();
            $role = $em->getRepository(Role::class)->findOneBy([
                'name' => 'ROLE_USER',
            ]);
            $user->setRole($role);

            $this->addFlash(
                'success',
                'Welcome to FAQ-O-Clock ' . $user->getUsername() .'!'
            );

            // On persiste en base
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('user/signin.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
