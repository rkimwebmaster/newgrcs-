<?php

namespace App\Form;

use App\Entity\ApprovisionnementGRCS;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ApprovisionnementGRCSType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('date')

            ->add('quantiteDiesel', RepeatedType::class, [
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
            ->add('quantiteEssence', RepeatedType::class, [
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
            
            // ->add('isConfirme')
            // ->add('isDiesel')
            // ->add('compteGRCS')
            // ->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApprovisionnementGRCS::class,
        ]);
    }
}
