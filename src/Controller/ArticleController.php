<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/", name="app_")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        $tricksList = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findAll();

        return $this->render('home/home.html.twig', [
            'trickList' => $tricksList
        ]);
    }

    /**
     * @Route("/figures", name="tricksList")
     */
    public function trickList(TrickRepository $trickRepository)
    {
        $tricksList = $trickRepository
            ->findAll();

        if (!$tricksList) {
            throw $this->createNotFoundException('Aucun Trick trouvé !');
        }

        return $this->render('trick/tricksList.html.twig', [
            'trickList' => $tricksList
        ]);
    }

    /**
     * @Route("/figure/{id<\d+>}", name="trick")
     */
    public function trickView(EntityManagerInterface $manager, Request $request, Trick $trick)
    {
        $commentList = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findBy([
                'trick' => $trick,
            ]);

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() AND $form->isValid()) {
            /** @var Comment $comment */
            $comment = $form->getData();

            $user = $this->getUser();
            $comment->setUser($user);
            //todo : ID de trick non trouvé lors de l'envoie d'un commentaire --> error trick_id = null

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre message a bien été envoyé.'
            );
            return $this->redirectToRoute('app_trick', [
                'trick' => $trick,
                'id' => $trick->getId(),
                'form' => $form->createView(),
                'commentList' => $commentList,
            ]);
        }

        return $this->render('trick/trick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
            'commentList' => $commentList,
        ]);
    }

    /**
     * @Route("/ajouter", name="add")
     */
    public function add(EntityManagerInterface $manager, Request $request)
    {
        $form = $this->createForm(TrickType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() AND $form->isValid()) {

            $path = $this->getParameter('kernel.project_dir') .'/public/uploads/images';
            $trick = $form->getData();
            $images = $trick->getImages();

            // images uploads
            foreach ($images as $image) {
                $file = $image->getFile();
                $name = $this->generateUniqueFileName(). '.' .$file->guessExtension();

                $file->move($path, $name);
                $image->setName($name);
            }

            // videos
            $videos = $trick->getVideos();
            foreach ($videos as $video) {
                $video->getUrl();
            }

            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le Trick a bien été enregistré.'
            );

            $form = $this->createForm(CommentType::class);
            $commentList = $this->getDoctrine()
                ->getRepository(Comment::class)
                ->findAll();

            return $this->render('trick/trick.html.twig', [
                'id' => $trick->getId(),
                'trick' => $trick,
                'form' => $form->createView(),
                'commentList' => $commentList,
            ]);
        }

        return $this->render('trick/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    /**
     * @Route("/modifier/{id<\d+>}", name="edit")
     */
    public function edit(EntityManagerInterface $manager, Request $request, Trick $trick)
    {
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() AND $form->isValid()) {
            $pathImage = $this->getParameter('kernel.project_dir') .'/public/uploads/images';

            $trick = $form->getData();

            // images uploads
            $images = $trick->getImages();
            foreach ($images as $image) {
                $file = $image->getFile();
                $name = $this->generateUniqueFileName(). '.' .$file->guessExtension();

                $file->move($pathImage, $name);
                $image->setName($name);
            }

            $manager->persist($trick);
            $manager->flush();

            $this->addFlash('success', 'Le Trick a bien été modifié.');

            $commentList = $this->getDoctrine()
                ->getRepository(Comment::class)
                ->findAll();

            return $this->render('trick/trick.html.twig', [
                'id' => $trick->getId(),
                'trick' => $trick,
                'commentList' => $commentList,
            ]);
        }

        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/supprimer/{id<\d+>}", name="delete")
     */
    public function delete(Request $request, Trick $trick)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') AND $form->handleRequest($request)->isValid()) {
            $entityManager->remove($trick);
            $entityManager->flush();

            $this->addFlash('notice', 'Le Trick a bien été supprimé.');

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('trick/delete.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }
}