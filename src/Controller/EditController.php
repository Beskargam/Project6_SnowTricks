<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\TrickType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/", name="app_")
 */
class EditController extends AbstractController
{
    /**
     * @Route("/modifier/figure-{id<\d+>}", name="editTrick")
     */
    public function edit(EntityManagerInterface $manager,
                         Request $request,
                         Trick $trick)
    {
        $images = $trick->getImages();
        foreach ($images as $image)
        {
            $file = $image->getFile();
            $image->setFile($file);
        }

        $editForm = $this->createForm(TrickType::class, $trick);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $path = $this->getParameter('kernel.project_dir') . '/public/uploads/images';

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
                if ($originalUrl !== null) {
                    preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $originalUrl, $transformedUrl);
                    $video->setUrl($transformedUrl[1]);
                }
            }

            $manager->flush();

            $this->addFlash(
                'success',
                'Le Trick a bien été modifié.');

            return $this->redirectToRoute('app_trick', [
                'id' => $trick->getId(),
            ]);
        }

        return $this->render('trick/editTrick.html.twig', [
            'form' => $editForm->createView(),
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/modifier/image-{id<\d+>}", name="deleteImage")
     */
    public function editImage(EntityManagerInterface $manager,
                              Request $request,
                              Image $image)
    {
        $deleteImageForm = $this->get('form.factory')->create();

        if ($request->isMethod('POST') AND $deleteImageForm->handleRequest($request)->isValid()) {
            $manager->remove($image);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'image a bien été supprimé.');

            return $this->redirectToRoute('app_editTrick', [
                'id' => $image->getTrick()->getId(),
            ]);

        }

        return $this->render('trick/deleteImage.html.twig', [
            'image' => $image,
            'form' => $deleteImageForm->createView(),
        ]);
    }

    /**
     * @Route("/supprimer/image-{id<\d+>}", name="deleteImage")
     */
    public function deleteImage(EntityManagerInterface $manager,
                                Request $request,
                                Image $image)
    {
        $deleteImageForm = $this->get('form.factory')->create();

        if ($request->isMethod('POST') AND $deleteImageForm->handleRequest($request)->isValid()) {
            $manager->remove($image);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'image a bien été supprimé.');

            return $this->redirectToRoute('app_editTrick', [
                'id' => $image->getTrick()->getId(),
            ]);

        }

        return $this->render('trick/deleteImage.html.twig', [
            'image' => $image,
            'form' => $deleteImageForm->createView(),
        ]);
    }

    /**
     * @Route("/supprimer/video-{id<\d+>}", name="deleteVideo")
     */
    public function deleteVideo(EntityManagerInterface $manager,
                                Request $request,
                                Video $video)
    {
        $deleteVideoForm = $this->get('form.factory')->create();

        if ($request->isMethod('POST') AND $deleteVideoForm->handleRequest($request)->isValid()) {
            $manager->remove($video);
            $manager->flush();

            $this->addFlash(
                'success',
                'La vidéo a bien été supprimé.');

            return $this->redirectToRoute('app_editTrick', [
                'id' => $video->getTrick()->getId(),
            ]);

        }

        return $this->render('trick/deleteVideo.html.twig', [
            'video' => $video,
            'form' => $deleteVideoForm->createView(),
        ]);
    }

    /**
     * @Route("/supprimer/trick-{id<\d+>}", name="deleteTrick")
     */
    public function delete(Request $request,
                           EntityManagerInterface $manager,
                           Trick $trick)
    {
        $deleteTrickForm = $this->get('form.factory')->create();

        if ($request->isMethod('POST') AND $deleteTrickForm->handleRequest($request)->isValid()) {
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
