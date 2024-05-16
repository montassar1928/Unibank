<?php

namespace App\Form;

use App\Entity\Operation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank; 
use Symfony\Component\Validator\Constraints\Length; 
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Regex;


class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('dateoperation', DateTimeType::class, [
                'label' => 'Date of Operation',
                'widget' => 'single_text',
                // Adjust options as needed
            ])
            ->add('description', TextareaType::class, [ // Use TextareaType for multiline text fields
                'label' => 'Description',
                // Add more options as needed
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a description.']),
                        new Length(['max' => 100, 'maxMessage' => 'Description should not exceed {{ limit }} characters.']),
                    ],
                ])
            ->add('statusoperation')
            ->add('montantopt', NumberType::class, [
                'label' => 'Montantopt',
                'scale' => 2, // Set the scale to 2 to allow up to 2 decimal places
                'constraints' => [
                    new NotBlank(['message' => 'Please enter the montantopt.']),
                    new Type([
                        'type' => 'float',
                        'message' => 'Please enter a valid number (float).',
                    ]),
                    new Regex([
                        'pattern' => '/^\d+(\.\d{1,2})?$/',
                        'message' => 'Please enter a valid number (up to 2 decimal places).',
                    ]),
                ],
                'html5' => true, // Enable HTML5 validation
                'attr' => [
                    'step' => 'any', // Allow any step value
                ],
                // Add more options as needed
            ])
            ->add('ref', null, [
                'required' => false,
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
