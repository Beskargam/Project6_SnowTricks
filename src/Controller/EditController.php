<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\AddImageType;
use App\Form\AddVideoType;
use App\Form\TrickType;
use App\Services\ImageHandler;
use App\Services\VideoHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
                         Trick $trick,
                         ImageHandler $imageHandler,
                         VideoHandler $videoHandler)
    {
        $path = $this->getParameter('kernel.project_dir') . '/public/uploads/images';
        $images = $trick->getImages();
        foreach ($images as $image) {
            $image->setFile(new UploadedFile($path . '/' . $image->getName(), $image->getName()));
        }

        $editForm = $this->createForm(TrickType::class, $trick);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            // images uploads
            $images = $trick->getImages();
            $imageHandler->handleImages($images, $path);

            // videos
            $videos = $trick->getVideos();
            $videoHandler->handleVideos($videos, $trick);

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
     * @Route("/modifier/figure-{id<\d+>}/ajouter-une-image", name="addImage")
     */
    public function addImage(EntityManagerInterface $manager,
                             Request $request,
                             Trick $trick,
                             ImageHandler $imageHandler)
    {

        $addImageForm = $this->createForm(AddImageType::class);
        $addImageForm->handleRequest($request);

        if ($request->isMethod('POST') && $addImageForm->isValid()) {

            // image uploads
            $path = $this->getParameter('kernel.project_dir') . '/public/uploads/images';
            $images = $trick->getImages();
            $imageHandler->handleImages($images, $path);

            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'success',
                'Enregistrement ràussi !');

            return $this->redirectToRoute('app_editTrick', [
                'id' => $trick->getId(),
            ]);

        }

        return $this->render('trick/addImage.html.twig', [
            'form' => $addImageForm->createView(),
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/supprimer/image-{id<\d+>}", name="deleteImage")
     */
    public function deleteImage(EntityManagerInterface $manager,
                                Request $request,
                                Image $image)
    {
        //$deleteImageForm = $this->createFormBuilder();
        $deleteImageForm = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $deleteImageForm->handleRequest($request)->isValid()) {
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
            //'form' => $deleteImageForm->getForm()->createView(),
            'form' => $deleteImageForm->createView(),
        ]);
    }

    /**
     * @Route("/modifier/figure-{id<\d+>}/ajouter-une-video", name="addVideo")
     */
    public function addVideo(EntityManagerInterface $manager,
                             Request $request,
                             Trick $trick,
                             VideoHandler $videoHandler)
    {

        $addVideoForm = $this->createForm(AddVideoType::class);
        $addVideoForm->handleRequest($request);

        if ($request->isMethod('POST') && $addVideoForm->isValid()) {
            $trick = $addVideoForm->getData();

            // videos
            $videos = $trick->getVideos();
            $videoHandler->handleVideos($videos);

            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'success',
                'Enregistrement ràussi !');

            return $this->redirectToRoute('app_editTrick', [
                'id' => $trick->getId(),
            ]);

        }

        return $this->render('trick/addVideo.html.twig', [
            'form' => $addVideoForm->createView(),
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/supprimer/video-{id<\d+>}", name="deleteVideo")
     */
    public function deleteVideo(EntityManagerInterface $manager,
                                Request $request,
                                Video $video)
    {
        $deleteVideoForm = $this->createFormBuilder();

        if ($request->isMethod('POST') && $deleteVideoForm->handleRequest($request)->isValid()) {
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
            'form' => $deleteVideoForm->getForm()->createView(),
        ]);
    }
}
