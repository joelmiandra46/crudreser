<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', 'home.index',methods:['GET'])]
    public function index(): Response
    {
       return $this->render('pages/home.html.twig',[
        
       ]);;
    }
}