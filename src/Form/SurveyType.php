<?php

namespace App\Form;

use App\Entity\Survey;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SurveyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class,[
                'label' => 'Titre' ,
                'attr' => ['placeholder' => 'Titre'],
                'constraints' => [
                    new Length([
                        'min' => 2 ,
                        'max' => 120
                    ]),
                    new NotBlank(['message' => 'Veuillez remplir ce champ'])
                    ],
                'required' => 'true'
            ])
            ->add('categorie', EntityType::class, [
                // each entry in the array will be an "email" field
                // these options are passed to each "email" type
            // ->add('categorie', EntityType::class, ,[
                'class'=>Categories::class,
                'placeholder'=>'selectionnner une categorie',

                'choice_label'=>'titre',
                'mapped' => true,
                // utiliser un checkbox à choix unique ou multiple
                'multiple'=>false,
                'expanded'=>true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Survey::class,
        ]);
    }
}
