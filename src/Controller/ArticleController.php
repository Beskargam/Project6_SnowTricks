<?php

namespace App\Controller;


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
        return $this->render('home/home.html.twig');
    }

    /**
     * @Route("/figure/{id}", name="trick")
     */
    public function view($id)
    {
        return $this->render('trick/trick.html.twig');
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
}