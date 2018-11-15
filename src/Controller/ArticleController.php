<?php
/**
 * Created by PhpStorm.
 * User: Aure
 * Date: 15.11.2018
 * Time: 16:42
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ArticleController
{
    /**
     * @Route("/")
     */

    public function homepage()
    {
        return new Response('OMG ! My first page is the Homepage !');
    }
}