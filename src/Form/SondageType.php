<?php

namespace App\Form;

use DateTime;
use App\Entity\Reponse;
use App\Entity\Sondage;
use App\Entity\Categories;
use App\Entity\Interroger;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SondageType extends AbstractType
{
    public function getQuestion(): Collection
    {
        return $this->question;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',TextType::class, [
                'label' => 'Titre du sondage :',
                'attr' => [
                'autofocus' => true,
                'placeholder' => 'Entrez le titre du sondage',
                ],
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
                    'expanded'=>false,
                ])
            
            ->add('description',TextareaType::class, [
                'label' => 'Description du sondange :',
                'required' => false,
                'attr' => [
                'placeholder' => 'Descriptif du sondage',
                'rows' => 10,
                'cols' => 20,
                ],
                ])

            ->add('statut',ChoiceType::class, [
                'label' => 'Statut du sondage :',
                'expanded' => true,
               'data'=>$options['statut'],
                'choices' => [
                'Brouillon' => 'Brouillon',
                'Ouvert' => 'Ouvert',
                'Fermé' => 'Fermé',
                ],
                ])
               
            ->add('messagefermeture',TextareaType::class, [
                'label' => 'Message de clôture du sondage :',
                'data' => 'Le sondage a été clôturé.',
                'attr' => [
                'placeholder' => 'Entrez le message de clôture du sondage',
                'rows' => 10,
                'cols' => 20,
                ],
            ]);

            // $formModifier = function(FormInterface $form, Categories $categorie = null){
            //     $question = (null === $categorie) ? [] : $categorie->getInterrogers();
            //     $form->add('question', EntityType::class, [
            //         'class' => Interroger::class,
            //         'choices' => $question,
            //         'choice_label' => 'intitule',
            //         'placeholder' => 'Catégorie (choisir une catégorie)',
            //         'label' => 'Question'
            //     ]);
            //};
            
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sondage::class,
            'statut' => null,

        ]);
    }
}
