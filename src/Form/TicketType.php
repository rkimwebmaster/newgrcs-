<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('date')
            ->add('nomChauffeur')
            ->add('numeroPlaqueImmatriculation')
            ->add('isGroupeElectrogene')
            ->add('dateRetrait')
            ->add('typeCarburant', ChoiceType::class,[
                'choices'=>[
                    'Choisir carburant'=>null,
                    'Diesel'=>'Diesel',
                    'Essence'=>'Essence',
                ],
                'attr'=>[
                    "required"=>"required"
                ]
            ])
            
            ->add('quantite', RepeatedType::class, [
                'type' => TypeIntegerType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Entrer la veleur, SVP',
                        ]),
                         
                    ],
                    'label' => 'Quantité carburant ',
                ],
                'second_options' => [
                    'label' => 'Repeter la quantité carburant',
                ],
                'invalid_message' => 'Les deux valeurs doivent correspondre.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                 
            ])
            
            // ->add('quantite')
            // ->add('totalMontant')
            // ->add('isServi')
            // ->add('utilisateur')
            // ->add('comptePetitClient')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
