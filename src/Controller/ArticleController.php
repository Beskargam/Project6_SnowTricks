<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
    public function trickView(Trick $trick)
    {
        $commentList = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findAll();

        return $this->render('trick/trick.html.twig', [
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
            $image = $trick->getImage();
            $file = $image->getFile();

            $name = $this->generateUniqueFileName(). '.' .$file->guessExtension();
            
            $file->move($path, $name);
            $image->setName($name);

            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le Trick a bien été enregistré.'
            );

            return $this->render('trick/trick.html.twig', [
                'id' => $trick->getId(),
                'trick' => $trick,
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
            $manager->persist($trick);
            $manager->flush();

            $this->addFlash('success', 'Le Trick a bien été modifié.');

            return $this->render('trick/trick.html.twig', [
                'id' => $trick->getId(),
                'trick' => $trick,
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