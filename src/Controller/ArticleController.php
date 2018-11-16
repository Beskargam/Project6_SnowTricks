<?php
/**
 * Created by PhpStorm.
 * User: Aure
 * Date: 15.11.2018
 * Time: 16:42
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/")
     */

    public function homepage()
    {
        return $this->render('home/home.html.twig');
    }
}