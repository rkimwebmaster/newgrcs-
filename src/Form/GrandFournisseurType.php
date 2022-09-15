<?php

namespace App\Form;

use App\Entity\GrandFournisseur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrandFournisseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('entreprise', EntrepriseType::class)
        ->add('identite', IdentiteType::class,[
            'label'=>'Coordonnées du representant',
        ])
        ->add('adresse', AdresseType::class)
            // ->add('compteGRCS')
            // ->add('messageGRCS')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GrandFournisseur::class,
        ]);
    }
}
