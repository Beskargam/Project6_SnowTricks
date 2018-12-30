<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use App\Services\ImageHandler;
use Doctrine\ORM\EntityManagerInterface;
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
    public function register(EntityManagerInterface $manager,
                             Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             ImageHandler $imageHandler,
                             \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // images uploads
            $path = $this->getParameter('kernel.project_dir') . '/public/uploads/images';
            $image = $user->getImage();
            $image->handle($path);


            $manager->persist($user);
            $manager->flush();

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
