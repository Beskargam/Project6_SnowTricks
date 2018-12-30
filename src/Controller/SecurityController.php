<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/", name="app_")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }

    /**
     * @Route("/oubli/motdepasse", name="forgotten_password")
     */
    public function forgottenPassword(EntityManagerInterface $manager,
                                      Request $request,
                                      UserPasswordEncoderInterface $encoder,
                                      \Swift_Mailer $mailer,
                                      TokenGeneratorInterface $tokenGenerator): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            $user = $manager->getRepository(User::class)->findOneByEmail($email);

            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('app_forgotten_password');
            }
            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $manager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('app_homepage');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Forgot Password'))
                ->setFrom('zenways@laposte.net')
                ->setTo($user->getEmail())
                ->setBody(
                    "Suivez le lien pour réinitialiser votre mot de passe : " . $url,
                    'text/html'
                );
            $mailer->send($message);

            $this->addFlash('notice', 'Un Email vous a été envoyé');

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('security/forgotten_password.html.twig');
    }

    /**
     * @Route("/reinitialisation/motdepasse/{token}", name="reset_password")
     */
    public function resetPassword(EntityManagerInterface $manager,
                                  Request $request,
                                  string $token,
                                  UserPasswordEncoderInterface $passwordEncoder)
    {

        if ($request->isMethod('POST')) {
            $manager = $this->getDoctrine()->getManager();

            $user = $manager->getRepository(User::class)->findOneByResetToken($token);

            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('app_reset_password');
            }

            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $manager->flush();

            $this->addFlash(
                'notice',
                'Mot de passe mis à jour');

            return $this->redirectToRoute('app_homepage');
        } else {

            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }
}
