<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Users; // Assurez-vous d'importer l'entité Users

use App\Form\ReclamationType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository, Security $security): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();
        
        // Récupérer les réclamations de l'utilisateur connecté
        $reclamations = $reclamationRepository->findBy(['userid' => $user->getId()]);
    
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        // Create a new Reclamation instance
        $reclamation = new Reclamation();
        
        // Get the currently authenticated user
        $user = $security->getUser();
        
        // Set the currently authenticated user as the user of the reclamation
        
        // Create the form, injecting the reclamation with the user
        $form = $this->createForm(ReclamationType::class, $reclamation);
        
        // Handle the form submission
        $form->handleRequest($request);
        
        // If the form is submitted and valid, save the reclamation
        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setUserid($user);

            // Set the creation date of the reclamation
            $reclamation->setDateCreation(new \DateTime());
            
            // Persist the reclamation
            $entityManager->persist($reclamation);
            $entityManager->flush();
        
            // Redirect to the reclamation index page
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }
        
        // Render the new reclamation form
        return $this->renderForm('reclamation/new.html.twig', [
            'form' => $form,
        ]);
    }
    
    
    
    #[Route('/reclamations', name: 'app_reclamations', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager->getRepository(Reclamation::class)->findAll();

        return $this->render('reclamation/show.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $reponse = $request->request->get('reponse');
    
            // Mettre à jour le champ de réponse de la réclamation avec la nouvelle valeur
            $reclamation->setReponse($reponse);
            $reclamation->setEtat('Traite');

    
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();
    
            // Si la requête est une requête Ajax, retourner une réponse JSON
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['success' => true]);
            }
    
            // Rediriger vers la page de détails de la réclamation après la modification
            return $this->redirectToRoute('app_reclamations', ['id' => $reclamation->getId()]);
        }
    
        // Si la requête n'est pas une requête POST, rediriger vers une autre page (par exemple, la page de détails de la réclamation)
        return $this->redirectToRoute('app_reclamations', ['id' => $reclamation->getId()]);
    }
    
    
    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamations', [], Response::HTTP_SEE_OTHER);
    }
}
