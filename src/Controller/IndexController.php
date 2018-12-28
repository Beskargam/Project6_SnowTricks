<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use App\Services\ImageHandler;
use App\Services\VideoHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/", name="app_")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(TrickRepository $trickRepository)
    {
        $trickList = $trickRepository
            ->findAll();

        return $this->render('home/home.html.twig', [
            'trickList' => $trickList,
        ]);
    }

    /**
     * @Route("/figures", name="tricksList")
     */
    public function trickList(TrickRepository $trickRepository)
    {
        $trickList = $trickRepository
            ->findAll();

        if (!$trickList) {
            throw $this->createNotFoundException('Aucun Trick trouvé !');
        }

        return $this->render('trick/tricksList.html.twig', [
            'trickList' => $trickList
        ]);
    }

    /**
     * @Route("/figure/{id<\d+>}", name="trick")
     */
    public function trickView(EntityManagerInterface $manager,
                              Request $request,
                              Trick $trick)

    {
        $commentForm = $this->createForm(CommentType::class);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() AND $commentForm->isValid()) {
            /** @var Comment $comment */
            $comment = $commentForm->getData();

            $user = $this->getUser();
            $comment->setUser($user);
            $comment->setTrick($trick);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre message a bien été envoyé.'
            );
            return $this->redirectToRoute('app_trick', [
                'id' => $trick->getId(),
            ]);
        }

        return $this->render('trick/trick.html.twig', [
            'form' => $commentForm->createView(),
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/ajouter", name="add")
     */
    public function trickAdd(EntityManagerInterface $manager,
                             Request $request,
                             ImageHandler $imageHandler,
                             VideoHandler $videoHandler)
    {
        $addTrickForm = $this->createForm(TrickType::class);
        $addTrickForm->handleRequest($request);

        if ($addTrickForm->isSubmitted() && $addTrickForm->isValid()) {
            $trick = $addTrickForm->getData();

            // images uploads
            $path = $this->getParameter('kernel.project_dir') . '/public/uploads/images';
            $images = $trick->getImages();
            $imageHandler->handleImages($images, $path);

            // videos
            $videos = $trick->getVideos();
            $videoHandler->handleVideos($videos, $trick);

            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le Trick a bien été enregistré.'
            );

            return $this->redirectToRoute('app_trick', [
                'id' => $trick->getId(),
            ]);
        }

        return $this->render('trick/addTrick.html.twig', [
            'form' => $addTrickForm->createView(),
        ]);
    }

    /**
     * @Route("/supprimer/trick-{id<\d+>}", name="deleteTrick")
     */
    public function trickDelete(Request $request,
                                EntityManagerInterface $manager,
                                Trick $trick)
    {
        $deleteTrickForm = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $deleteTrickForm->handleRequest($request)->isValid()) {
            $manager->remove($trick);
            $manager->flush();

            $this->addFlash(
                'notice',
                'Le Trick a bien été supprimé.');

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('trick/deleteTrick.html.twig', [
            'trick' => $trick,
            'form' => $deleteTrickForm->createView(),
        ]);
    }
}