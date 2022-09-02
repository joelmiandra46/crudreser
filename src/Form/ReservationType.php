<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Salles;
use App\Entity\Reservation;
use App\Repository\ClientRepository;
use App\Repository\SallesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReservationType extends AbstractType
{
    private $transformer;
    public function __construct(FrenchToDateTimeTransformer $transformer){
        $this->transformer = $transformer;
    }

    /**
     * Undocumented function
     *
     * @param [type] $label
     * @param [type] $placeholder
     * @param array $options
     * @return array
     */
    private function getConfigurationDate( $label,$placeholder,$options = []){
        return array_merge([
            'label' => $label,
            'attr' => [
                'class'=>'form-control',
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
                'choice_label' => function($client){
                    return strtoupper($client->getNom())." ".$client->getPrenom();
                },
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
            //->add('createdAt', DateType::class,$this-> getConfigurationDate("Date de reservation","Selectionnez la date d'aujourd'hui"))
            ->add('startDate', TextType::class,$this-> getConfigurationDate("Debut date d'occupation","Selectionnez la date d'occupation"))
            ->add('endDate', TextType::class,$this-> getConfigurationDate("Fin date d'occupation","Selectionnez la date de fin d'occupation"))
           // ->add('montant', NumberType::class,$this-> getConfiguration("Frais :","Le prix de reservation du salle en Ar"))
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

        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
