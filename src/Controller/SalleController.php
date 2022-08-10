<?php

namespace App\Controller;

use App\Entity\Salles;
use App\Form\SalleType;
use App\Repository\SallesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class SalleController extends AbstractController
{
    /**
     * Cette fonction sert a afficher toutes les salles
     *
     * @param SallesRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/salle', name: 'salle_index', methods:['GET'])]
    public function index(SallesRepository $repository, PaginatorInterface $paginator,Request $request): Response
    {

        $salles = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), /*nombre de page*/
            10
        );

            return $this->render('pages/salle/index.html.twig', [
            'salles' => $salles
        ]);
    }

    /**
     * Ce controlleur permet de creer un nouveau salle
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
      #[Route('/salle/nouveau', name:"salle_new", methods:['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $salle = new Salles();

        $form = $this->createForm(SalleType::class, $salle);

        $form->handleRequest($request);   //gerer requette
        if ($form->isSubmitted() && $form->isValid()) { 
            $salle = $form->getData();

            $manager->persist($salle);//commit vers repository
            $manager->flush();//push

            $this->addFlash(
                'success',
                'Votre salle a été créé avec succès'
            );

           return $this->redirectToRoute('salle_index');
        }
        return $this->render('pages/salle/new.html.twig', [
            'form'=> $form->createView()
        ]);
    }
    /**
     *controlleur qui permet de modifier les informations d'une salle
     */
    #[Route('/salle/edit/{id}', name:"salle_edit", methods:['GET','POST'])]
    public function edit(Salles $salle, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(SalleType::class, $salle);

        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) { 
            $salle = $form->getData();

            $manager->persist($salle);//commit vers repository
            $manager->flush();//push

            $this->addFlash(
                "success",
                "Les modifications de la salle  numero <strong>{$salle->getNumero()}</strong> ont été bien enregistrées"
            );
            return $this->redirectToRoute('salle_index');
        }


        return $this->render('pages/salle/edit.html.twig', [
            'form'=> $form->createView(),
            'salle'=> $salle
        ]);
    }

    #[Route('/salle/delete/{id}', name:"salle_delete", methods:['GET'])]
    public function delete(EntityManagerInterface $manager, Salles $salle): Response
    {
        if(!$salle){
            $this->addFlash(
                "success",
                "La salle  numero <strong>{$salle->getNumero()}</strong> n'existe pas"
            );
            return $this->redirectToRoute('salle_index');
        }
        $manager->remove($salle);
        $manager->flush();//push


        $this->addFlash(
            "danger",
            "La salle  numero <strong>{$salle->getNumero()}</strong> a été bien supprimé"
        );
        return $this->redirectToRoute('salle_index');
    }
}
        
    
