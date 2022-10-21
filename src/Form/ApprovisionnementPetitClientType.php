<?php

namespace App\Form;

use App\Entity\ApprovisionnementPetitClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

class ApprovisionnementPetitClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('date')
            // ->add('isDiesel')
            
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
            // ->add('quantiteRepete', IntegerType::class,[
            //     'attr'=>[
            //         'mapped'=>false,
            //         'label'=>"Repetez la même quantité.",
            //     ]
            // ])
            // ->add('montant')
            // ->add('bordereau')
            ->add('brochure', FileType::class, [
                'label' => 'Fichier bordereau (JPEG, PNG file)',
    
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
    
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
    
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image document',
                    ])
                ],
            ])
            ->add('numeroBordereau')
            // ->add('isConfirme')
            // ->add('utilisateur')
            // ->add('comptePetitClient')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApprovisionnementPetitClient::class,
        ]);
    }
}
