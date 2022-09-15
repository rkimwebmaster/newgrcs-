<?php

namespace App\Form;

use App\Entity\PetitClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PetitClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entreprise', EntrepriseType::class)
            ->add('qteStockRecommandeDiesel')
            ->add('qteStockRecommandeEssence')
            ->add('identite', IdentiteType::class,[
                'label'=>'Coordonnées du representant',
            ])
            ->add('adresse', AdresseType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PetitClient::class,
        ]);
    }
}
