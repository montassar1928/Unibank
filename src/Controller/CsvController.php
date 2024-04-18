<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CsvController extends AbstractController
{
    #[Route('/csv', name: 'app_csv')]
    public function downloadCsv(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les utilisateurs ayant le rôle "CLIENT" et le statut "Actif"
        $users = $entityManager->getRepository(Users::class)->findBy([
            'role' => 'CLIENT',
            'statut' => 'Actif'
        ]);

        // Générer le contenu CSV
        $csvContent = "ID,Nom,Prenom,Email,Date de creation,Adresse,Raison Sociale,Telephone,Date de Naissance,Statut,CIN,Role,Banned\n";
        foreach ($users as $user) {
            $csvContent .= "{$user->getId()},{$user->getNom()},{$user->getPrenom()},{$user->getEmail()},";
            $csvContent .= $user->getDateCreation() ? $user->getDateCreation()->format('Y-m-d') : 'N/A';
            $csvContent .= ",{$user->getAdresse()},{$user->getRaisonSociale()},{$user->getTelephone()},";
            $csvContent .= $user->getDateDeNaissance() ? $user->getDateDeNaissance()->format('Y-m-d') : 'N/A';
            $csvContent .= ",{$user->getStatut()},{$user->getCin()},{$user->getRole()},{$user->getBanned()}\n";
        }

        // Créer une réponse HTTP avec le contenu CSV
        $response = new Response($csvContent);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="liste_des_clients.csv"');

        return $response;
    }
}
