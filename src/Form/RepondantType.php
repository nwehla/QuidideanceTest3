<?php

namespace App\Form;

use App\Entity\Repondant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepondantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('repondant')
            ->add('commentaire')
            ->add('email')
            ->add('acceptationpartagedonnee')
            ->add('datecreation')
            ->add('datemiseajour')
            ->add('datefermeture')
            ->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Repondant::class,
        ]);
    }
}
