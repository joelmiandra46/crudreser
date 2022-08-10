<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClientType extends AbstractType
{

    /**
     * Permet d'avoir la configuration de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @param int $minlength
     * @param int $maxlength
     * @param array $options
     * @return array
     */
    private function getConfiguration($label, $placeholder,$minlength, $maxlength, $options = []){
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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,$this->getConfiguration("Nom", "Taper le nom du client","3","50"))
            ->add('prenom',TextType::class,$this->getConfiguration("Prenom", "Taper le prenom du client","3","50"))
            ->add('tel',TextType::class,$this->getConfiguration("Tel", "Taper le numero Tel du client","5","50"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
