<?php

namespace App\Form;

use App\Entity\ComptePetitClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComptePetitClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('quantiteDiesel')
            // ->add('quantiteEssence')
            // ->add('dateDernierApprovisionnement')
            // ->add('petitClient')
            ->add('compteGRCS')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ComptePetitClient::class,
        ]);
    }
}
