<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Salles;
use App\Entity\Reservation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker= Factory::create('FR-fr');

        $adminRole = new Role();
        $adminRole ->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);
        
        $adminUser = new User();
        $adminUser->setFirstName('Mario')
                ->setLastName('Miandra')
                ->setUsername('joelmiandra')
                ->SetEmail('mariomiandra@gmail.com')
                ->setPassword($this->hasher->hashPassword($adminUser, 'password'))
                ->addUserRole($adminRole);
        $manager->persist($adminUser);
        //Gerer les Utilisateurs
        $users = [];
        for ($i=1; $i<=10 ; $i++) { 
            $user = new User();

            $hash = $this->hasher->hashPassword($user, 'password');

            $user->setFirstName($faker->firstname)
                ->setLastName($faker->lastname)
                ->setUsername($faker->username)
                ->setEmail($faker->email)
                ->setPassword($hash);
            $manager->persist($user);
            $users[] = $user;
        }
        //salles
        $salles=[];
        for ($i=1; $i <30 ; $i++) { 
            $salle = new Salles();

            $salle  ->setDesignation($faker->word)
                    ->setNumero(mt_rand(1, 100))
                    ->setCapacite(mt_rand(20 , 250))
                    ->setCaracteristique('testcaracter')
                    ->setRmq('   ' . join('  ', $faker->words(4)) . '   ')
                    ->setFrais(mt_rand(20000 , 100000))
                    ->setEtat('test etat'. $i)
                    ->setColor($faker->HexColor)
            ;
            $salles[] = $salle;
            $manager->persist($salle);

        }
        //clients
        $clients = [];
        for ($i=1; $i <60 ; $i++) { 
            $client = new Client();

            $client ->setNom($faker->firstname)
                    ->setPrenom($faker->lastname)
                    ->setTel($faker->phonenumber)
            ;
            $manager->persist($client);
            $clients[] = $client;
        }
                    //Gestion des Reservations
                    for ($j=1; $j<=mt_rand(20, 100); $j++) { 
                        $reservation = new Reservation();
    
                        $createdAt = $faker->dateTimeBetween('- 6 months');
                        $startDate = $faker->dateTimeBetween('- 3 months');
    
                        $duration = mt_rand(3, 10);
                        $endDate = (clone $startDate)->modify("+$duration days");
                        $montant = $salle->getFrais() * $duration;
                        $statutreservation = $faker->word(1);
    
                        $client = $clients[mt_rand(0, count($clients) -1)];
                        $salle = $salles[mt_rand(0, count($salles) -1)];
                        $user = $users[mt_rand(0, count($users) - 1)];
    
                        $reservation->setClient($client)
                                    ->setSalle($salle)
                                    ->setStartDate($startDate)
                                    ->setEndDate($endDate)
                                    ->setCreatedAt($createdAt)
                                    ->setMontant($montant)
                                    ->setStatutReservation($statutreservation)
                                    ->setAuthor($user)

                                    ;
                        $manager->persist($reservation);
                    }
            
        $manager->flush();
    }
}
