<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse; // Importer RedirectResponse ici
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use Symfony\Component\VarDumper\VarDumper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;




class UsersphotoController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/show', name: 'show', methods: ['GET'])]
    public function showPhotos(): Response
    {
        // Récupérer les utilisateurs avec une photo depuis la base de données
       
        
        return $this->render('users/show.html.twig', [
        ]);
    }
    #[Route('/menu', name: '', methods: ['GET'])]
    public function index( ): Response
    {
    
        return $this->render('users/template.html.twig', [
        ]);
    }
  
    #[Route("/{id}", name: "app_users_accept", methods: ["POST"])]
    public function accept(Request $request, EntityManagerInterface $entityManager, int $id): RedirectResponse
    {
        // Charger l'utilisateur depuis la base de données en utilisant l'identifiant
        $user = $entityManager->getRepository(Users::class)->find($id);
    
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }
    
        // Mettre à jour le statut de l'utilisateur
        $user->setStatut('Actif');
        $user->setBanned('False');

        $entityManager->flush();
    
        // Ajouter le préfixe de pays "+216" au numéro de téléphone
        $telephone = '+216' . $user->getTelephone();
    
        // Envoyer un SMS avec Twilio
        $twilioSid = "AC5cf45072dc7d879953d4f9433c7cd504";
        $twilioToken = "99b1ba8acc88a1c19a54f7f1c834ba73";
        $twilioNumber =  "+13342343159";
    
        $client = new Client($twilioSid, $twilioToken);
        $message = "Nous sommes ravis de vous informer que votre compte Unibank a été activé avec succès par notre équipe administrative. Vous pouvez dès à présent vous connecter à votre compte pour accéder à toutes les fonctionnalités offertes par notre plateforme.
        .";
    
        $client->messages->create(
            $telephone, // Numéro de téléphone du destinataire avec le préfixe de pays "+216"
            [
                "from" => $twilioNumber,
                "body" => $message,
            ]
        );
        echo "Le SMS a été envoyé avec succès à " . $telephone;
    
        // Rediriger vers la page d'index des utilisateurs
        return $this->redirectToRoute('app_users_index');
    }

    #[Route("/monta/{id}", name: "delete", methods: ["POST"])]
    public function delete(Request $request, EntityManagerInterface $entityManager, int $id): RedirectResponse
    {
        // Charger l'utilisateur depuis la base de données en utilisant l'identifiant
        $user = $entityManager->getRepository(Users::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Supprimer l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();

        // Rediriger vers la page d'index des utilisateurs
        return $this->redirectToRoute('app_users_index');
    }
    #[Route('/profile', name: 'app_profile')]

    #[IsGranted("IS_AUTHENTICATED_FULLY")] // Appliquer l'authentification uniquement à cette route

    public function example(): Response
    {
        // Rendre la vue profile.html.twig en utilisant le service 'render'
        return $this->render('FrontOffice/profile.html.twig');
    }
    
}






















