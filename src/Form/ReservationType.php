<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Salles;
use App\Form\ClientType;
use App\Entity\Reservation;
use App\Repository\ClientRepository;
use App\Repository\SallesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Polyfill\Intl\Icu\DateFormat\MonthTransformer;

class ReservationType extends AbstractType
{
    /**
     * Permet d'avoir la configuration de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    private function getConfiguration($label, $placeholder, $options = []){
        return array_merge([
            'label' => $label,
            'attr' => [
                'class'=>'form-control',
                'placeholder'=> $placeholder,
            ],

            ], $options);
    }
    private function getConfigurationDate( $label,$placeholder,$options = []){
        return array_merge([
            'format' => 'dd-mm-yyyy',
            'label' => $label,
            'widget' => 'single_text',
            // prevents rendering it as type="date", to avoid HTML5 date pickers
            'html5' => false,
            // adds a class that can be selected in JavaScript
            'attr' => [
                'class' =>'js-datepicker form-control ',
                'placeholder'=> $placeholder,
            ],
            ], $options);
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class,[
                'attr'=>[
                    'class'=>'form-select'
                ],
                'class' => Client::class,
                'query_builder' => function (ClientRepository $r) {
                    return $r->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => 'nom',
            ])
            ->add('salle', EntityType::class,[
                'attr'=>[
                    'class'=>'form-select'
                ],
                'class' => Salles::class,
                'query_builder' => function (SallesRepository $r) {
                    return $r->createQueryBuilder('u')
                        ->orderBy('u.designation', 'ASC');
                },
                'choice_label' => 'designation',
            ])
            ->add('createdAt', DateType::class,$this-> getConfigurationDate("Date de reservation","Selectionnez la date d'aujourd'hui"))
            ->add('startDate', DateType::class,$this-> getConfigurationDate("Debut date d'occupation","Selectionnez la date d'occupation"))
            ->add('endDate', DateType::class,$this-> getConfigurationDate("Fin date d'occupation","Selectionnez la date de fin d'occupation"))
            ->add('montant', NumberType::class,$this-> getConfiguration("Frais :","Le prix de reservation du salle en Ar"))
            ->add('statutReservation', ChoiceType::class, [
                'attr'=>[
                    'class'=>'form-select'
                ],
                'choices'  => [
                    'Réservé' => 'Réservé',//nul
                    'libre' => 'libre',
                ],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
