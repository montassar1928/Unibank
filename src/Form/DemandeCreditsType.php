<?php

namespace App\Form;

use App\Entity\Demande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeCreditsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'montant souhaite',

            ])
            ->add('revenu', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'revenu annuel'
            ])
            ->add('duree', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'duree souhaite'
            ])
            ->add('statut', null, [
                'attr' => ['class' => 'form-control', 'style' => 'display:none'],
                'data' => 'Actif',
                'label' => false]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $demande = $event->getData();
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
