<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends AbstractType
{
    private function getConfiguration($label, $placeholder, $options = []){
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder'=> $placeholder,
            ],

            ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class,$this->getConfiguration("Ancien mot de passe","Tapez votre mot de passe actuel ... "))
            ->add('newPassword', PasswordType::class,$this->getConfiguration("Nouveau mot de passe","Tapez votre nouveau mot de passe ... "))
            ->add('confirmPassword', PasswordType::class,$this->getConfiguration("Confirmation du mot de passe","Confirmer votre nouveau mot de passe ... "))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
