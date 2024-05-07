<?php

namespace App\Form;

use App\Entity\CompteEpargne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class CompteEpargneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin', IntegerType::class, [
                'label' => 'Compte ID',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'CIN'
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prenom'
                ]
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Age',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Age'
                ]
            ])
            ->add('telephone', IntegerType::class, [
                'label' => 'Telephone',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'telephone'
                ]
            ])
            ->add('status', TextType::class, [
                'label' => 'Status',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Status'
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Image URL',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Image URL'
                ],
                
                'required'=>false,
                 'mapped'=>false,
            ])
            ->add('montant', IntegerType::class, [
                'label' => 'Montant',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Montant'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Send',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompteEpargne::class,
        ]);
    }
}
