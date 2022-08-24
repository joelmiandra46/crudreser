<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
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
            ->add('lastName',  TextType::class, $this->getConfiguration('Nom',"Votre nom de famille ..."))
            ->add('firstName', TextType::class, $this->getConfiguration('Prénom',"Votre prénom ..."))
            ->add('email',  EmailType::class, $this->getConfiguration('Email',"Votre adresse email ..."))
            ->add('password', PasswordType::class, $this->getConfiguration('Mot de passe',"Choisissez un bon mot de passe !"))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration('Confirmation de mot de passe',"Veuillez confirmer votre mot de passe "))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
