<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="home_page")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index ()
    {
        return  $this->render('index/index.html.twig', ['body' => 'Hello world!!!']);
    }
}
