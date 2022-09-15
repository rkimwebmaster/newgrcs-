<?php

namespace App\Form;

use App\Entity\GRCS;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GRCSType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('entreprise', EntrepriseType::class)
            ->add('identite',IdentiteType::class,[
                'label'=>'Coordonnées du representant',
            ])
            ->add('adresse', AdresseType::class)
            ->add('qteStockRecommandeDiesel')
            ->add('qteStockRecommandeEssence')
            ->add('prixDiesel')
            ->add('prixEssence')
            ->add('monnaie', ChoiceType::class, [
                'choices' => [
                    'Dollars US' => '$ (Dollars americain)',
                    'Franc Congolais' => 'CDF',                    
                ],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GRCS::class,
        ]);
    }
}
