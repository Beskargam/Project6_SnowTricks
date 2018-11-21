<?php

namespace App\Controller;


use App\Entity\Trick;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function trickList()
    {
        $tricksList = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findAll();

        if (!$tricksList) {
            throw $this->createNotFoundException('Aucun Trick trouvé !');
        }

        return $this->render('trick/tricksList.html.twig', [
            'trickList' => $tricksList
        ]);
    }

    /**
     * @Route("/figure/{id}", name="trick")
     */
    public function trickView($id)
    {
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->find($id);

        if (!$trick) {
            throw $this->createNotFoundException('Le Trick ' .$id. ' n\'existe pas');
        }

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/ajouter", name="add")
     */
    public function add()
    {
        return $this->render('trick/add.html.twig');
    }

    /**
     * @Route("/editer/{id}", name="edit")
     */
    public function edit($id)
    {
        return $this->render('trick/edit.html.twig');
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id)
    {
        return $this->render('trick/edit.html.twig');
    }
}