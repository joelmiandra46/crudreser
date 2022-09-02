<?php

namespace App\Form;

use App\Entity\Salles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SalleType extends AbstractType
{
    private function getConfigurationtext($label, $placeholder,$minlength, $maxlength, $options = []){
        return array_merge([
            'label' => $label,
            'attr' => [
                'class'=>'form-control',
                'placeholder'=> $placeholder,
                'minlength'=> $minlength,
                'maxlength'=> $maxlength
            ],

            ], $options);
    }
    private function getConfigurationint($label, $placeholder, $options = []){
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
            ->add('designation', TextType::class,$this->getConfigurationtext("Designation", "Taper le designation de votre salle","2","50"))
            ->add('numero', IntegerType::class, $this->getConfigurationint("Numero","Le numero de votre salle"))
            ->add('capacite', IntegerType::class, $this->getConfigurationint("Capacité","Taper la capacité maximale de la salle"))
            ->add('caracteristique', TextType::class,$this->getConfigurationtext("Caracteristique", "Taper le caracteristique de votre salle","5","100"))
            ->add('rmq', TextType::class,$this->getConfigurationtext("Remarque", "Remarque pour votre salle","2","100"))
            ->add('etat', TextType::class,$this->getConfigurationtext("Etat", "Taper l'Etat de votre salle","4","50"))
            ->add('frais', NumberType::class, $this->getConfigurationint("Frais","Taper le frais pour la salle"))
            ->add('color', ColorType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salles::class,
        ]);
    }
}
