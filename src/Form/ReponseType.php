<?php

namespace App\Form;

use App\Entity\Reponse;
use App\Entity\Interroger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReponseType extends AbstractType
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
            'required' => true
        ])
        ->add('question', EntityType::class, [
            // each entry in the array will be an "email" field
            // these options are passed to each "email" type
            'class'=>Interroger::class,
            'choice_label'=>'intitule',
            'mapped' => true,
            // utiliser un checkbox à choix unique ou multiple
            'multiple'=> false,
            'expanded'=> true,
            'required' => true
           ])
        // ->add('question', CollectionType::class, [
        //     //each entry in the array will be an "email" field
        //     'entry_type' => InterrogerType::class,
        //     //these options are passed to each "email" type
        //     'entry_options' => [
        //        'attr' => ['class' => 'intitule'],
        //     ],
        // ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
