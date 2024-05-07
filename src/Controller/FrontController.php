<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CompteCourantRepository;
use App\Repository\CompteEpargneRepository;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('CompteCourant/frontEmpty.html.twig');
    }
    #[Route('/Courant', name: 'app_front_Courant')]
    public function indexCourant(CompteCourantRepository $CompteCourantRepository): Response
    {
        $items = $CompteCourantRepository->findAll();

        return $this->render('CompteCourant/front.html.twig',[
            "items" => $items
        ]);
    }
    #[Route('/Epargne', name: 'app_front_Epargne')]
    public function indexEpargne(CompteEpargneRepository $CompteEpargneRepository): Response
    {
        $items = $CompteEpargneRepository->findAll();

        return $this->render('CompteEpargne/front.html.twig',[
            "items" => $items
        ]);
    }
}
