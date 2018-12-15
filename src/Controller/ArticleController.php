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
    public function homepage(TrickRepository $trickRepository,
                             ImageRepository $imageRepository)
    {
        $tricksList = $trickRepository
            ->findAll();
        $imageList = $imageRepository
            ->findAll();

        return $this->render('home/home.html.twig', [
            'trickList' => $tricksList,
            'imageList' => $imageList,
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
    public function trickView(CommentRepository $commentRepository,
                              ImageRepository $imageRepository,
                              VideoRepository $videoRepository,
                              EntityManagerInterface $manager,
                              Request $request,
                              Trick $trick)

    {
        $commentList = $commentRepository
            ->findBy([
                'trick' => $trick,
            ]);
        $imageList = $imageRepository
            ->findBy([
                'trick' => $trick
            ]);
        $videoList = $videoRepository
            ->findBy([
                'trick' => $trick
            ]);

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() AND $form->isValid()) {
            /** @var Comment $comment */
            $comment = $form->getData();

            $user = $this->getUser();
            $comment->setUser($user);
            $comment->setTrick($trick)->getId();

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
                'imageList' => $imageList,
                'videoList' => $videoList,
            ]);
        }

        return $this->render('trick/trick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
            'commentList' => $commentList,
            'imageList' => $imageList,
            'videoList' => $videoList,
        ]);
    }

    /**
     * @Route("/ajouter", name="add")
     */
    public function add(CommentRepository $commentRepository,
                        ImageRepository $imageRepository,
                        VideoRepository $videoRepository,
                        EntityManagerInterface $manager,
                        Request $request)
    {
        $form = $this->createForm(TrickType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() AND $form->isValid()) {

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
                $discardUrl = 'https://www.youtube.com/watch?v=';
                $transformedUrl = str_replace($discardUrl, "", $originalUrl);
                $video->setUrl($transformedUrl);

            }

            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le Trick a bien été enregistré.'
            );

            $form = $this->createForm(CommentType::class);
            $commentList = $commentRepository
                ->findBy([
                    'trick' => $trick,
                ]);
            $imageList = $imageRepository
                ->findBy([
                    'trick' => $trick
                ]);
            $videoList = $videoRepository
                ->findBy([
                    'trick' => $trick
                ]);

            return $this->redirectToRoute('app_trick', [
                'id' => $trick->getId(),
                'trick' => $trick,
                'form' => $form->createView(),
                'commentList' => $commentList,
                'imageList' => $imageList,
                'videoList' => $videoList,
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
    public function edit(CommentRepository $commentRepository,
                         ImageRepository $imageRepository,
                         VideoRepository $videoRepository,
                         EntityManagerInterface $manager,
                         Request $request,
                         Trick $trick)
    {
        $imageList = $imageRepository
            ->findBy([
                'trick' => $trick
            ]);
        $videoList = $videoRepository
            ->findBy([
                'trick' => $trick
            ]);

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
            $videos = $trick->getVideos(); // TODO : Attempted to call an undefined method named "getVideos" of class "Doctrine\ORM\PersistentCollection"
            foreach ($videos as $video) {
                $originalUrl = $video->getUrl();
                $discardUrl = 'https://www.youtube.com/watch?v=';
                $transformedUrl = str_replace($discardUrl, "", $originalUrl);
                $video->setUrl($transformedUrl);
            }

            $manager->persist($trick);
            $manager->flush();

            $this->addFlash('success', 'Le Trick a bien été modifié.');

            // redirect rendering
            $form = $this->createForm(CommentType::class);
            $commentList = $commentRepository
                ->findBy([
                    'trick' => $trick,
                ]);

            return $this->redirectToRoute('app_trick', [
                'form' => $form->createView(),
                'trick' => $trick,
                'id' => $trick->getId(),
                'commentList' => $commentList,
                'imageList' => $imageList,
                'videoList' => $videoList,
            ]);
        }

        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
            'imageList' => $imageList,
            'videoList' => $videoList,
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