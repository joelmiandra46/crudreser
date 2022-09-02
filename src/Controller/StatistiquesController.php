<?php

namespace App\Controller;

use App\Repository\SallesRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatistiquesController extends AbstractController
{
    #[Route('/stats', name: 'stats')]
    public function statistiques(SallesRepository $salleRepo): Response
    {
        $salles = $salleRepo->findAll();

        $salleDesignation = [];
        $salleColor = [];
        $salleCount = [];

        foreach ($salles as $salle) {
            $salleDesignation[] = $salle->getDesignation();
            $salleColor[] = $salle-> getColor();
            $salleCount[] = count($salle->getClt());
        }

        return $this->render('statistiques/stats.html.twig',[
            'salleDesignation'=> json_encode($salleDesignation),
            'salleColor' => json_encode($salleColor),
            'salleCount' => json_encode($salleCount)
        ]);
    }
}
