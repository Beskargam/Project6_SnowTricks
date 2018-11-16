<?php

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */

    public function homepage()
    {
        return $this->render('home/home.html.twig');
    }

    /**
     * @Route("/figure/{id}", name="app_trick")
     */

    public function view($id)
    {
        return $this->render('trick/trick.html.twig');
    }

    /**
     * @Route("/ajouter", name="app_add")
     */

    public function add()
    {
        return $this->render('trick/add.html.twig');
    }

    /**
     * @Route("/editer/{id}", name="app_edit")
     */

    public function edit($id)
    {
        return $this->render('trick/edit.html.twig');
    }
}