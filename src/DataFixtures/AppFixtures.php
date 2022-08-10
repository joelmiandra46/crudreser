<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Client;
use App\Entity\Salles;
use App\Entity\Reservation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker= Factory::create('FR-fr');
        //salles
        $salles=[];
        for ($i=1; $i <50 ; $i++) { 
            $salle = new Salles();

            $salle->setDesignation($faker->word)
                   ->setNumero(mt_rand(1, 100))
                   ->setCapacite(mt_rand(20 , 250))
                   ->setCaracteristique('testcaracter')
                   ->setRmq('   ' . join('  ', $faker->words(4)) . '   ')
                   ->setFrais(mt_rand(20000 , 100000))
                   ->setEtat('test etat'. $i)
                   ;
            $salles[] = $salle;
            $manager->persist($salle);

        }
        //clients
        $clients = [];
        for ($i=1; $i <50 ; $i++) { 
            $client = new Client();

            $client->setNom($faker->firstname)
                   ->setPrenom($faker->lastname)
                   ->setTel($faker->phonenumber)
            ;
            $manager->persist($client);
            $clients[] = $client;
        }
                    //Gestion des Reservations
                    for ($j=1; $j<=mt_rand(0, 100); $j++) { 
                        $reservation = new Reservation();
    
                        $createdAt = $faker->dateTimeBetween('- 6 months');
                        $startDate = $faker->dateTimeBetween('- 6 months');
    
                        $duration = mt_rand(3, 10);
                        $endDate = (clone $startDate)->modify("+$duration days");
                        $montant = $salle->getFrais() * $duration;
                        $statutreservation = $faker->word(1);
    
                        $client = $clients[mt_rand(0, count($clients) -1)];
    
                        $reservation->setClient($client)
                                    ->setSalle($salle)
                                    ->setStartDate($startDate)
                                    ->setEndDate($endDate)
                                    ->setCreatedAt($createdAt)
                                    ->setMontant($montant)
                                    ->setStatutReservation($statutreservation)
                                    ;
                        $manager->persist($reservation);
                    }
        $manager->flush();
    }
}
