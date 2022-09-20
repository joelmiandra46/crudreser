<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', 'home_index',methods:['GET'])]
    public function index(EntityManagerInterface $manager): Response
    {
        //DQL
        $clients = $manager->createQuery('SELECT COUNT(c) FROM App\Entity\Client c')->getSingleScalarResult();
        $salles = $manager->createQuery('SELECT COUNT(s) FROM App\Entity\Salles s')->getSingleScalarResult();
        $users = $manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
        $reservations = $manager->createQuery('SELECT COUNT(r) FROM App\Entity\Reservation r')->getSingleScalarResult();

        return $this->render('pages/home.html.twig',[
            /*'stats' => [
                //'cle' => $variable
                'clients' => $clients,
                'salles' => $salles,
                'users' => $users,
                'reservations' => $reservations,
            ]*/
            //ou compacter le DQLss
            'stats' => compact('clients', 'salles', 'users', 'reservations')
        ]);;
    }
}