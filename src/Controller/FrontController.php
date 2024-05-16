<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CompteCourantRepository;
use App\Repository\CompteEpargneRepository;

use Symfony\Component\Security\Core\Security;

class FrontController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function index(
        CompteCourantRepository $compteCourantRepository,
        CompteEpargneRepository $compteEpargneRepository,
        Security $security
    ): Response {
        // Récupérer l'utilisateur actuellement authentifié à partir de la session
        $user = $security->getUser();

        // Récupérer les comptes courants de l'utilisateur actuel
        $compteCourants = $compteCourantRepository->findBy(['iduser' => $user]);

        // Récupérer les comptes épargne de l'utilisateur actuel
        $compteEpargnes = $compteEpargneRepository->findBy(['iduser' => $user]);

        return $this->render('CompteCourant/front.html.twig', [
            "compteCourants" => $compteCourants,
            "compteEpargnes" => $compteEpargnes
        ]);
    }
}
