<?php
namespace App\Form;

use App\Entity\Operation;
use App\Entity\VirementInternational;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Validator\Constraints as Assert;

class InternationalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('montantopt', MoneyType::class, [
                'label' => 'Montant Option',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => 'numeric']),
                    new Assert\GreaterThanOrEqual(['value' => 0]),
                ],
            ])
            ->add('ref', EntityType::class, [
                'class' => VirementInternational::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose an option',
                'label' => 'Virement International',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])

            ->add('send', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^\d{1,9}$/', // Accept between 1 and 9 digits
                        'message' => 'The send field must contain between 1 and 9 numbers.',
                    ]),
                ],
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
