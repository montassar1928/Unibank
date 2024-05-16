<?php

namespace App\Controller;

use App\Entity\CompteEpargne;
use App\Form\CompteEpargneType;
use App\Repository\CompteEpargneRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompteEpargneController extends AbstractController
{
    #[Route('/compte/compteeeEpargne/Epargne/Admin', name: 'app_backCompteEpargne')]
    public function index(Request $request,CompteEpargneRepository $CompteEpargneRepository): Response
    {
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $CompteEpargneRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        return $this->render('CompteEpargne/index.html.twig',[
            "compteEpargne" => $items
        ]);
    }
    #[Route('/admin/CompteEpargne/add', name: 'app_back_CompteEpargne_add')]
    public function add(Request $request,ManagerRegistry $mr): Response
    {
        $CompteEpargne = new CompteEpargne();
        $form = $this->createForm(CompteEpargneType::class, $CompteEpargne);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($CompteEpargne);
            $em->flush();    
            return $this->redirectToRoute('app_backCompteEpargne');
        }
    
        return $this->render('CompteEpargne/backForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/CompteEpargne/admin/update/{id}', name: 'app_back_CompteEpargne_update')]
    public function update(Request $request,ManagerRegistry $mr,$id,CompteEpargneRepository $CompteEpargneRepository): Response
    {
        $CompteEpargne = $CompteEpargneRepository->find($id);
        $form = $this->createForm(CompteEpargneType::class, $CompteEpargne);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($CompteEpargne);
            $em->flush();    
            return $this->redirectToRoute('app_back_CompteEpargne');
        }
    
        return $this->render('CompteEpargne/backForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/CompteEpargne/delete/{id}', name: 'app_back_CompteEpargne_delete')]
    public function delete(CompteEpargneRepository $CompteEpargneRepository,int $id,ManagerRegistry $mr): Response
    {        
        $CompteEpargne = $CompteEpargneRepository->find($id);
        $entityManager = $mr->getManager();
        $entityManager->remove($CompteEpargne);
        $entityManager->flush();

        return $this->redirectToRoute('app_back_CompteEpargne');
    }
}
