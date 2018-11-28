<?php

namespace App\Controller;


use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

        if (!$tricksList) {
            throw $this->createNotFoundException('Aucun Trick trouvé !');
        }

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
        return $this->render('trick/trick.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/ajouter", name="add")
     */
    public function add(Request $request)
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() AND $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Le Trick a bien été enregistré.');

            return $this->render('trick/trick.html.twig', [
               'id' => $trick->getId(),
            ]);
        }

        return $this->render('trick/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier/{id<\d+>}", name="edit")
     */
    public function edit(Request $request, Trick $trick)
    {
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() AND $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le Trick a bien été modifié.');

            return $this->render('trick/trick.html.twig', [
                'id' => $trick->getId(),
            ]);
        }

        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/supprimer/{id<\d+>}", methods={"POST"}, name="delete")
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

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }
}