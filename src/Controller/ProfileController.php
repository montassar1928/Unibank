<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class ProfileController extends AbstractController
{
    private $entityManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route("/profile/changepassword", name: "app_change_password")]
    public function changePassword(Request $request): Response
    {
        $user = $this->getUser();
        $errorMessages = [];

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            $oldPassword = $request->request->get('old-password');
            $newPassword = $request->request->get('new-password');
            $confirmPassword = $request->request->get('confirm-new-password');

            // Vérifier si les champs sont vides
            if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
                $errorMessages['all-fields'] = 'Tous les champs sont obligatoires.';
            }

            // Utiliser l'encoder pour vérifier le mot de passe actuel
            if (!$this->passwordEncoder->isPasswordValid($user, $oldPassword)) {
                $errorMessages['old-password'] = 'L\'ancien mot de passe est incorrect.';
            }

            // Vérifier si les nouveaux mots de passe correspondent
            if ($newPassword !== $confirmPassword) {
                $errorMessages = 'Les nouveaux mots de passe ne correspondent pas.';
            }

            // Si aucune erreur, procéder à la modification du mot de passe
            if (empty($errorMessages)) {
                // Encoder le nouveau mot de passe
                $encodedPassword = $this->passwordEncoder->encodePassword($user, $newPassword);

                // Mettre à jour le mot de passe de l'utilisateur directement dans l'entité User
                $user->setPassword($encodedPassword);

                // Mettre à jour l'entité dans la base de données
                $this->entityManager->flush();

                // Ajouter un message flash de succès
                $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');

                // Rediriger vers la page de changement de mot de passe
                return $this->redirectToRoute('app_change_password');
            }
        }

        // Afficher la page de modification de mot de passe avec le formulaire
        return $this->render('profile/index.html.twig', [
            'errorMessages' => $errorMessages,
        ]);
    }
    #[Route('/change-photo', name: 'change_photo', methods: ['POST'])]
    public function changePhoto(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si une image a été uploadée
        $uploadedFile = $request->files->get('image');
        if ($uploadedFile instanceof UploadedFile) {
            // Générer un nom de fichier unique
            $fileName = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
            
            // Définir le répertoire de destination dans le répertoire public/img
            $destination = $this->getParameter('kernel.project_dir') . '/public/img';
            
            try {
                // Déplacer le fichier téléchargé vers le répertoire de destination
                $uploadedFile->move($destination, $fileName);
            } catch (FileException $e) {
                // En cas d'erreur lors du déplacement du fichier
                return new Response("Une erreur s'est produite lors de l'enregistrement de l'image.", Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            
            // Mettre à jour le chemin de l'image de profil de l'utilisateur dans la base de données
            $user = $this->getUser();
            $user->setPhoto($fileName);
            
            // Persistez les modifications de l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();
            
            // Rediriger l'utilisateur vers une page appropriée
            return $this->redirectToRoute('app_profile');
        }
    
        // Gérer le cas où aucune image n'est téléchargée
        // Rediriger l'utilisateur vers une page d'erreur ou une page appropriée
        return new Response("Aucune image n'a été téléchargée.", Response::HTTP_BAD_REQUEST);
    }
   
}
