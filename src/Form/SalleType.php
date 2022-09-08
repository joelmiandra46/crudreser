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
    private function getConfigurationText($label, $placeholder,$minlength, $maxlength, $options = []){
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
    private function getConfigurationInt($label, $placeholder, $options = []){
        return array_merge([
            'label' => $label,
            'attr' => [
                'class'=>'form-control',
                'placeholder'=> $placeholder,
            ],

            ], $options);
    }
    private function getConfigurationColor($label, $options = []){
        return array_merge([
            'label' => $label,
            ], $options);
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { 
        $builder
            ->add('designation', TextType::class,$this->getConfigurationText("Designation", "Taper le designation de votre salle","2","50"))
            ->add('numero', IntegerType::class, $this->getConfigurationInt("Numero","Le numero de votre salle"))
            ->add('capacite', IntegerType::class, $this->getConfigurationInt("Capacité","Taper la capacité maximale de la salle"))
            ->add('caracteristique', TextType::class,$this->getConfigurationText("Caracteristique", "Taper le caracteristique de votre salle","5","100"))
            ->add('rmq', TextType::class,$this->getConfigurationText("Remarque", "Remarque pour votre salle","2","100"))
            ->add('etat', TextType::class,$this->getConfigurationText("Etat", "Taper l'Etat de votre salle","4","50"))
            ->add('frais', NumberType::class, $this->getConfigurationInt("Frais","Taper le frais pour la salle"))
            ->add('color', ColorType::class, $this->getConfigurationColor('Couleur'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salles::class,
        ]);
    }
}
