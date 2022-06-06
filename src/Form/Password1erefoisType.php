<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class Password1erefoisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('new_password' , RepeatedType::class , [
                'type' => PasswordType::class ,
                'mapped' => false ,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique.' ,
                'label' => 'Mon nouveau mot de passe' ,
                'required' => true ,
                'first_options' => [ 'label' => 'Mon nouveau mot de passe' ,
                'attr' => [
                    'placeholder' => ' Veuillez saisir votre nouveau mot de passe'
                    ]
                ] ,                
                'second_options' => [ 'label' => 'Confirmez votre nouveau mot de passe' ,
                'attr' => [
                    'placeholder' => 'Confirmez votre nouveau mot de passe'
                    ]
                ]
            ])
            ->add('submit' , SubmitType::class , [
                'label' => "Mettre à jour"
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
