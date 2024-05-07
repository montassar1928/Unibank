<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TCPDF;

class PdfController extends AbstractController
{
    #[Route('/pdf', name: 'app_pdf')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les utilisateurs ayant le rôle "CLIENT" et le statut "Actif"
        $users = $entityManager->getRepository(Users::class)->findBy([
            'role' => 'CLIENT',
            'statut' => 'Actif'
        ]);

        // Créer une nouvelle instance de TCPDF avec le format de page A3
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A3', true, 'UTF-8', false);

        // Définir les métadonnées du PDF
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Liste des clients');
        $pdf->SetSubject('Liste des clients');
        $pdf->SetKeywords('PDF, users, Symfony');

        // Ajouter une page
        $pdf->AddPage();

        // Définir la position de départ du tableau en haut de la page
        $startY = 20; // Position en Y

        // Taille des cellules du tableau
        $cellHeight = 5; // Hauteur de chaque cellule
        $fontSize = 8; // Taille de la police

        // Définir les colonnes du tableau
        $pdf->setFillColor(255, 255, 255); // Couleur de fond
        $pdf->SetFont('Helvetica', 'B', $fontSize); // Police et style

        // Ajouter un titre
        $pdf->Cell(0, 20, 'Liste_des_clients', 0, 1, 'C'); // Ajouter le titre centré avec un espacement en dessous

        // Noms des colonnes
        $columnNames = [
            'ID',
            'Nom',
            'Prénom',
            'Email',
            'Date de création',
            'Adresse',
            'Raison Sociale',
            'Téléphone',
            'Date de Naissance',
            'Statut',
            'CIN',
            'Rôle',
            'Banned'
        ];

        // Largeurs des colonnes
        $colWidths = [
            10, // ID
            20, // Nom
            15, // Prénom
            40, // Email
            35, // Date de création
            25, // Adresse
            35, // Raison Sociale
            30, // Téléphone
            25, // Date de Naissance
            15, // Statut
            20, // CIN
            10, // Rôle
            15  // Banned
        ];

        // Ajouter les noms de colonnes au PDF
        $x = 1; // Position de départ X, décalage vers la gauche
        foreach ($columnNames as $index => $columnName) {
            if (array_key_exists($index, $colWidths)) {
                $pdf->SetXY($x, $startY + 10); // Décaler vers le bas pour le titre
                $pdf->Cell($colWidths[$index], $cellHeight, $columnName, 1, 0, 'C', true);
                $x += $colWidths[$index]; // Mettre à jour la position X pour la prochaine colonne
            } else {
                $pdf->Cell(1, $cellHeight, $columnName, 1, 0, 'C', true); // Utilisation d'une largeur par défaut de 10 pour les colonnes non définies
                $x += 1; // Mettre à jour la position X pour la prochaine colonne
            }
        }
        $pdf->Ln(); // Aller à la ligne suivante

        // Ajouter le contenu au PDF
        $pdf->SetFont('Helvetica', '', 6); // Police et style pour les données
        foreach ($users as $user) {
            // Données utilisateur
            $data = [
                $user->getId(),
                $user->getNom(),
                $user->getPrenom(),
                $user->getEmail(),
                $user->getDateCreation() ? $user->getDateCreation()->format('Y-m-d') : '',
                $user->getAdresse(),
                $user->getRaisonSociale(),
                $user->getTelephone(),
                $user->getDateDeNaissance() ? $user->getDateDeNaissance()->format('Y-m-d') : '',
                $user->getStatut(),
                $user->getCin(),
                $user->getRole(),
                $user->getBanned(),
            ];

            // Ajouter les données au PDF
            $x = 1; // Position de départ X, décalage vers la gauche
            foreach ($data as $index => $datum) {
                if (array_key_exists($index, $colWidths)) {
                    $pdf->SetXY($x, $pdf->GetY());
                    $pdf->Cell($colWidths[$index], $cellHeight, $datum, 1, 0, 'C');
                    $x += $colWidths[$index]; // Mettre à jour la position X pour la prochaine colonne
                } else {
                    $pdf->Cell(1, $cellHeight, $datum, 1, 0, 'C'); // Utilisation d'une largeur par défaut de 10 pour les colonnes non définies
                    $x += 1; // Mettre à jour la position X pour la prochaine colonne
                }
            }
            $pdf->Ln(); // Aller à la ligne suivante après chaque ligne de données
        }

        // Sortie du PDF en tant que fichier (vous pouvez également utiliser d'autres méthodes de sortie)
        $pdf->Output('liste_utilisateurs.pdf', 'D');

        // Retourner une réponse Symfony
        return new Response();
    }
}
