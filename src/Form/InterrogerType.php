<?php

namespace App\Form;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Entity\Categories;
use App\Entity\Interroger;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class InterrogerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule', TextType::class,[
                'label' => 'Question' ,
                'attr' => ['placeholder' => 'Question'],
                'constraints' => [
                    new Length([
                        'min' => 2 ,
                        'max' => 30
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
                'multiple'=>true,
                'expanded'=>true,
            ])
            
            ->add('reponses', CollectionType::class, array(
                'entry_type' => ReponseType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false

            ))
        ;

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                
                foreach ($event->getData()->getReponses() as $reponse) {
                    $reponse->setQuestion($event->getData());
                }
                
            }
        );

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Interroger::class,
        ]);
    }
}
