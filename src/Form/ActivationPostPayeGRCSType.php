<?php

namespace App\Form;

use App\Entity\ActivationPostPayeGRCS;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

use Symfony\Component\Validator\Constraints\Length;

class ActivationPostPayeGRCSType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut')
            // ->add('dateFin')
            ->add('nombreJour')
            // ->add('isCloture')
            ->add('quantiteMaxDieselAutorise', RepeatedType::class, [
                'type' => IntegerType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Entrer la veleur, SVP',
                        ]),
                         
                    ],
                    'label' => 'Quantité diesel ',
                ],
                'second_options' => [
                    'label' => 'Repeter la quantité diesel',
                ],
                'invalid_message' => 'Les deux valeurs doivent correspondre.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                 
            ])
            ->add('quantiteMaxEssenceAutorise', RepeatedType::class, [
                'type' => IntegerType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Entrer la veleur, SVP',
                        ]),
                         
                    ],
                    'label' => 'Quantité essence ',
                ],
                'second_options' => [
                    'label' => 'Repeter la quantité essence',
                ],
                'invalid_message' => 'Les deux valeurs doivent correspondre.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                 
            ])
            // ->add('')
            // ->add('/')

            // ->add('compteGRCS')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActivationPostPayeGRCS::class,
        ]);
    }
}
