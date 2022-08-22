<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class RegistrationType extends AbstractType
{
    private function getConfiguration($label, $placeholder,$minlength, $maxlength, $options = []){
        return array_merge([
            'label' => $label,
            'label_attr' => [
                'class' => 'form_label'
            ],
            'attr' => [
                'class'=>'form-control',
                'placeholder'=> $placeholder,
                'minlength'=> $minlength,
                'maxlength'=> $maxlength
            ]
            ], $options);
    }
    private function getConfigurationpassword($placeholder1,$placeholder2, $options = []){
        return array_merge([
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'Mot de Passe',
                'attr' => [
                    'class'=>'form-control',
                    'placeholder'=> $placeholder1,
                ]
            ],
            'second_options' => [
                'label' => 'Confirmation de mot de passe',
                'attr' => [
                    'class'=>'form-control',
                    'placeholder'=> $placeholder2,
                ]
            ],
            'invalid_message' => 'Les mots de passe ne correspondent pas.',
            ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class,$this->getConfiguration("Nom / Prenom", "Entrer le nom d'utilisateur", "2","50"))
            ->add('pseudo', TextType::class,$this->getConfiguration("Pseudo-Facultatif", "Entrer le nom d'utilisateur", "2","50") )
            ->add('email', EmailType::class,$this->getConfiguration("Adresse email", "Entrer votre Adresse email", "2","180") )
            ->add('plainPassword', RepeatedType::class,$this->getConfigurationpassword("Entrer une mot de passe", "Confirmer le mot de passe") )
            ->add('submit', SubmitType::class, [
                'attr' =>[
                    'class'=> 'btn btn-primary'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
