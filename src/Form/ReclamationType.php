<?php
namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType; // Importez le type TextareaType

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description', TextareaType::class) // Utilisez TextareaType pour un champ de description
            ->add('Etat', HiddenType::class, [
                'data' => 'Non traité'
            ])
            ->add('Reponse', HiddenType::class, [
                'empty_data' => ''
            ])
            ->add('userid', HiddenType::class)
            ->add('dateCreation', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
