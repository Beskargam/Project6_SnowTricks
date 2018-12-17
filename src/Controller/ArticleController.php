<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\ImageRepository;
use App\Repository\TrickRepository;
use App\Repository\VideoRepository;
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
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() AND $form->isValid()) {
            /** @var Comment $comment */
            $comment = $form->getData();

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
            'form' => $form->createView(),
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/ajouter", name="add")
     */
    public function add(EntityManagerInterface $manager,
                        Request $request)
    {
        $form = $this->createForm(TrickType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $path = $this->getParameter('kernel.project_dir') . '/public/uploads/images';
            $trick = $form->getData();

            // images uploads
            $images = $trick->getImages();
            foreach ($images as $image) {
                    $file = $image->getFile();
                    $name = $this->generateUniqueFileName() . '.' . $file->guessExtension();

                    $file->move($path, $name);
                    $image->setName($name);
            }

            // videos
            $videos = $trick->getVideos();
            foreach ($videos as $video) {
                $originalUrl = $video->getUrl();
                // $discardUrl = 'https://www.youtube.com/watch?v=';
                // $transformedUrl = str_replace($discardUrl, "", $originalUrl);
                preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $originalUrl, $transformedUrl);
                $video->setUrl($transformedUrl[1]);
            }

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
    public function edit(EntityManagerInterface $manager,
                         Request $request,
                         Trick $trick)
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->getData();

        $form->handleRequest($request);
        if ($form->isSubmitted() AND $form->isValid()) {
            $pathImage = $this->getParameter('kernel.project_dir') . '/public/uploads/images';
            $trick =

                // images uploads
            $images = $trick->getImages();
            foreach ($images as $image) {
                $file = $image->getFile();
                $name = $this->generateUniqueFileName() . '.' . $file->guessExtension();

                $file->move($pathImage, $name);
                $image->setName($name);
            }

            // videos
            $videos = $trick->getVideos();
            foreach ($videos as $video) {
                $originalUrl = $video->getUrl();
                $discardUrl = 'https://www.youtube.com/watch?v=';
                $transformedUrl = str_replace($discardUrl, "", $originalUrl);
                $video->setUrl($transformedUrl);
            }

            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le Trick a bien été modifié.');

            return $this->redirectToRoute('app_trick', [
                'id' => $trick->getId(),
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
    public function delete(Request $request,
                           EntityManagerInterface $manager,
                           Trick $trick)
    {
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') AND $form->handleRequest($request)->isValid()) {
            $manager->remove($trick);
            $manager->flush();

            $this->addFlash('notice', 'Le Trick a bien été supprimé.');

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('trick/delete.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }
}