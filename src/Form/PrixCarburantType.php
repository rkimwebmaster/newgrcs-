<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\GRCS;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrixCarburantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('qteStockRecommandeDiesel')
        ->add('qteStockRecommandeEssence')
        ->add('prixEssence')
        ->add('prixDiesel')
            ->add('monnaie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GRCS::class,
        ]);
    }
}
