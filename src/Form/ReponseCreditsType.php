<?php

namespace App\Form;

use App\Entity\Reponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseCreditsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('idD', null, [
            'attr' => ['class' => 'form-control'],
            'label' => 'id demande',

        ])
            ->add('montant_r', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'montant demandé',

            ])
            ->add('dureeR', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'duree demande'
            ])
            ->add('statut', null, [
                'attr' => ['class' => 'form-control', 'style' => 'display:none'],
                'data' => 'Actif',
                'label' => false]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $reponse = $event->getData();
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
