<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class Users1Type extends AbstractType
{
    private $passwordEncoder;
    private $entityManager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'First name',
               
            ])
            ->add('prenom', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Last name'
            ])
            ->add('email', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Email address'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Role', null, [
                'attr' => ['class' => 'form-control', 'style' => 'display:none'],
                'data' => 'BANQUE',
                'label' => false,
            ])
            ->add('statut', null, [
                'attr' => ['class' => 'form-control', 'style' => 'display:none'],
                'data' => 'Actif',
                'label' => false,
            ])
            ->add('Banned', null, [
                'attr' => ['class' => 'form-control', 'style' => 'display:none'],
                'data' => 'False',
                'label' => false,
            ])
            ->add('adresse')
            ->add('Raison_Sociale')
            ->add('telephone')
            ->add('dateDeNaissance', BirthdayType::class, [
                'label' => 'Date de Naissance',
                'required' => false, // Si le champ est facultatif
                'widget' => 'single_text', // Afficher le champ comme un champ de texte simple
                'attr' => [
                    'class' => 'form-control', 
                    'placeholder' => 'Date de Naissance',
                    'id' => 'date-de-naissance'
                ]
            ])          
              ->add('cin')
            ->add('photo', FileType::class, [
                'label' => 'Photo (JPG, PNG file)',
                'required' => false,
                'mapped' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '100024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPG or PNG image',
                    ])
                ],
            ]);

            $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $user = $event->getData();
            
                // Récupérer le fichier téléchargé
                $photoFile = $form->get('photo')->getData();
            
                // Vérifier si un nouveau fichier a été téléchargé
                if ($photoFile instanceof UploadedFile) {
                    // Utiliser le nom d'origine du fichier
                    $fileName = $photoFile->getClientOriginalName();
            
                    // Mettre à jour le nom du fichier de la photo dans l'entité Users
                    $user->setPhoto($fileName);
                }
            }); }
            

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }

   
       
    }
