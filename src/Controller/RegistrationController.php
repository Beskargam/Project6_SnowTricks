<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/", name="user_")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/enregistrement", name="register")
     */
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new \Swift_Message('Confirmation d\'inscription SnowTricks'))
                ->setFrom('zenways@laposte.net')
                ->setTo($user->getEmail())
                ->setBody(
                    "Nous confirmons votre inscription sur le site SnowTricks",
                    'text/html'
                );
            $mailer->send($message);

            $this->addFlash(
                'success',
                'Bienvenue ! Nous vous avons envoyé un Email de confirmation');

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }
}
