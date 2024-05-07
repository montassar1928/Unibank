<?php

namespace App\Controller;

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
    #[Route('/admin/CompteCourant', name: 'app_back_CompteCourant')]
    public function index(Request $request,CompteCourantRepository $CompteCourantRepository): Response
    {
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $CompteCourantRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        return $this->render('CompteCourant/index.html.twig',[
            "items" => $items
        ]);
    }
    #[Route('/admin/CompteCourant/add', name: 'app_back_CompteCourant_add')]
    public function add(Request $request,ManagerRegistry $mr): Response
    {
        $CompteCourant = new CompteCourant();
        $form = $this->createForm(CompteCourantType::class, $CompteCourant);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($CompteCourant);
            $em->flush();    
            return $this->redirectToRoute('app_back_CompteCourant');
        }
    
        return $this->render('CompteCourant/backForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/admin/CompteCourant/update/{id}', name: 'app_back_CompteCourant_update')]
    public function update(Request $request,ManagerRegistry $mr,$id,CompteCourantRepository $CompteCourantRepository): Response
    {
        $CompteCourant = $CompteCourantRepository->find($id);
        $form = $this->createForm(CompteCourantType::class, $CompteCourant);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($CompteCourant);
            $em->flush();    
            return $this->redirectToRoute('app_back_CompteCourant');
        }
    
        return $this->render('CompteCourant/backForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/CompteCourant/delete/{id}', name: 'app_back_CompteCourant_delete')]
    public function delete(CompteCourantRepository $CompteCourantRepository,int $id,ManagerRegistry $mr): Response
    {        
        $CompteCourant = $CompteCourantRepository->find($id);
        $entityManager = $mr->getManager();
        $entityManager->remove($CompteCourant);
        $entityManager->flush();

        return $this->redirectToRoute('app_back_CompteCourant');
    }
}
