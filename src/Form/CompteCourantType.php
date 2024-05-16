<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\CompteCourant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository; // Ajoutez cette ligne
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CompteCourantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('iduser', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
                'label' => 'User',
                'attr' => [
                    'class' => 'form-control'
                ],
                'placeholder' => 'Select User',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },
            ])
            ->add('cin', IntegerType::class, [
                'label' => 'Compte ID',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'CIN'
                ]
            ])
          
            ->add('prenom', TextType::class, [
                'label' => 'comptType',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Type'
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
                'required' => false,
                'mapped' => false,
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompteCourant::class,
        ]);
    }
}
