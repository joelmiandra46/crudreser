<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Salles;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker= Factory::create('FR-fr');
        for ($i=1; $i <50 ; $i++) { 
            $salle = new Salles();

            $salle->setDesignation($faker->word)
                   ->setNumero(mt_rand(1, 50))
                   ->setCapacite(mt_rand(20 , 250))
                   ->setCaracteristique('testcaracter')
                   ->setRmq('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                   ->setFrais(mt_rand(20000 , 100000))
                   ->setEtat('test etat'. $i)
                   ;
            $manager->persist($salle);

        }

        $manager->flush();
    }
}
