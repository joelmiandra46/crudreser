<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    /**
     * controlleur qui affiche les clients
     *
     * @param SallesRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/client', name: 'client_index', methods:['GET'])]
    public function index(ClientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $clients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), /*nombre de page*/
            10
        );
        return $this->render('pages/client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    /**
     * Controlleur qui permet de creer un nouveau client
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    
    #[Route('/client/nouveau', name:"client_new", methods:['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $client = new Client();

        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($request);   //gerer requette
        if ($form->isSubmitted() && $form->isValid()) { 
            $salle = $form->getData();

            $manager->persist($client);//commit 
            $manager->flush();//push

            $this->addFlash(
                'success',
                'un nouveau client a été ajouté avec succès'
            );

           return $this->redirectToRoute('client_index');
        }
        return $this->render('/pages/client/new.html.twig', [
            'form'=> $form->createView()
        ]);
    }
        
    
    
    #[Route('/client/edit/{id}', name:"client_edit", methods:['GET','POST'])]
    public function edit(Client $client, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) { 
            $client = $form->getData();

            $manager->persist($client);//commit vers repository
            $manager->flush();//push

            $this->addFlash(
                "success",
                "Les modifications du client numero <strong>{$client->getId()}</strong> ont été bien enregistrées"
            );
            return $this->redirectToRoute('client_index');
        }
        return $this->render('/pages/client/edit.html.twig', [
            'form'=> $form->createView(),
            'client'=> $client
        ]);
    }

    #[Route('/client/delete/{id}', name:"client_delete", methods:['GET'])]
    public function delete(EntityManagerInterface $manager, Client $client): Response
    {
        if(!$client){
            $this->addFlash(
                "success",
                "Le client numero <strong>{$client->getId()}</strong> n'existe pas"
            );
            return $this->redirectToRoute('salle_index');
        }
        $manager->remove($client);
        $manager->flush();//push


        $this->addFlash(
            "warning",
            "Le client  <strong>{$client->getNom()}</strong> a été bien supprimé"
        );
        return $this->redirectToRoute('client_index');
        
    }
}
