<?php

namespace App\Controller;

use App\Entity\Users; // Ajout de l'importation de la classe Users
use App\Entity\CompteCourant;
use App\Form\CompteCourantType;
use App\Repository\CompteCourantRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompteCourantController extends AbstractController
{
    #[Route('/CompteCourant/admin', name: 'app_backCompteCourant')]
    public function index(Request $request, CompteCourantRepository $CompteCourantRepository): Response
    {
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');
    
        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');
    
        $compteCourants = $CompteCourantRepository->findBySearchAndSort($searchBy, $searchQuery, $sortBy, $sortOrder);
    
        return $this->render('CompteCourant/index.html.twig', [
            "compteCourants" => $compteCourants
        ]);
    }
    

    #[Route('/admin/CompteCourant/add', name: 'app_back_CompteCourant_add')]
    public function add(Request $request, ManagerRegistry $mr): Response
    {
        $CompteCourant = new CompteCourant();
        $form = $this->createForm(CompteCourantType::class, $CompteCourant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($CompteCourant);
            $em->flush();
            return $this->redirectToRoute('app_backCompteCourant');
        }

        return $this->render('CompteCourant/backForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/CompteCourant/admin/update/{id}', name: 'app_back_CompteCourant_update')]
    public function update(Request $request, ManagerRegistry $mr, $id, CompteCourantRepository $CompteCourantRepository): Response
    {
        $CompteCourant = $CompteCourantRepository->find($id);
        $form = $this->createForm(CompteCourantType::class, $CompteCourant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($CompteCourant);
            $em->flush();
            return $this->redirectToRoute('app_backCompteCourant');
        }

        return $this->render('CompteCourant/backForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/CompteCourant/admin/delete/{id}', name: 'app_back_CompteCourant_delete')]
    public function delete(CompteCourantRepository $CompteCourantRepository, int $id, ManagerRegistry $mr): Response
    {
        // Recherche de l'entité avec l'ID spécifié
        $CompteCourant = $CompteCourantRepository->find($id);
    
        // Vérifie si l'entité a été trouvée
        if (!$CompteCourant) {
            // Gérer la situation où l'entité n'est pas trouvée, par exemple, en renvoyant une réponse d'erreur ou en redirigeant vers une autre page
            // Ici, je renvoie une réponse d'erreur 404
            throw $this->createNotFoundException('CompteCourant not found for id ' . $id);
        }
    
        // Si l'entité a été trouvée, supprimez-la
        $entityManager = $mr->getManager();
        $entityManager->remove($CompteCourant);
        $entityManager->flush();
    
        // Redirigez ensuite vers une page appropriée
        return $this->redirectToRoute('app_backCompteCourant');
    }
    
}
