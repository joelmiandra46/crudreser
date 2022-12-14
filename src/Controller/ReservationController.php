<?php

namespace App\Controller;

use App\Entity\Salles;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'reservation_index', methods:['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(ReservationRepository $repository, PaginatorInterface $paginator,Request $request): Response
    {
        $reservations = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), /*nombre de page*/
            50
        );

            return $this->render('pages/reservation/index.html.twig', [
            'reservations' => $reservations
        ]);
    }


    /**
     * controlleur pour cree un nouveau reservation
     * 
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/reservation/nouveau', name:"reservation_new", methods:['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $reservation = new Reservation();

        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);   //gerer requette

        if ($form->isSubmitted() && $form->isValid()) { 
            $reservation = $form->getData();
            //si lesdates ne sont pas disponibles, message d'erreur
            if(!$reservation->isBookableDates()){
                $this->addFlash(
                    'warning',
                    "Les dates que vous avez choisi <br> ne 
                    peuvent etre r??serv??es: <br> elle sont d??ja prises "
                );
            } else {
                $reservation->setAuthor($this->getUser());
                //sinon enregistrement et redirection
                $manager->persist($reservation);//commit vers repository
                $manager->flush();//push

                $this->addFlash(
                    'success',
                    'Votre reservation a ??t?? cr???? avec succ??s'
                );

                return $this->redirectToRoute('reservation_index');
            }
        }

        return $this->render('pages/reservation/new.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    /**
     *controlleur qui permet de modifier les informations d'une salle
     */
    #[Route('/reservation/edit/{id}', name:"reservation_edit", methods:['GET','POST'])]
    public function edit(Reservation $reservation, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) { 
            $reservation = $form->getData();
            
            $manager->persist($reservation);//commit vers repository
            $manager->flush();//push
            
            $this->addFlash(
                "success",
                "Les modifications de la r??servation numero <strong>{$reservation->getId()}</strong> ont ??t?? bien enregistr??es"
            );
            return $this->redirectToRoute('reservation_index');
        }


        return $this->render('pages/reservation/edit.html.twig', [
            'form'=> $form->createView(),
            'reservation'=> $reservation
        ]);
    }

    #[Route('/reservation/delete/{id}', name:"reservation_delete", methods:['GET'])]
    public function delete(EntityManagerInterface $manager, Reservation $reservation): Response
    {
        if(!$reservation){
            $this->addFlash(
                "danger",
                "La Reservation  numero <strong>{$reservation->getId()}</strong> n'existe pas"
            );
            return $this->redirectToRoute('salle_index');
        }
        $manager->remove($reservation);
        $manager->flush();//push


        $this->addFlash(
            "success",
            "La reservation du client <strong>{$reservation->getClient()}</strong> de la salle <strong>{$reservation->getSalle()}</strong>  a ??t?? bien supprim??"
        );
        return $this->redirectToRoute('reservation_index');
    }
}
