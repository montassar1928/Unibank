<?php

namespace App\Controller;

use App\Entity\VirementInternational;
use App\Form\VirementInternationalType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/virement/international')]
class VirementInternationalController extends AbstractController
{
    #[Route('/', name: 'app_virement_international_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $virementInternationals = $entityManager
            ->getRepository(VirementInternational::class)
            ->findAll();

        return $this->render('virement_international/index.html.twig', [
            'virement_internationals' => $virementInternationals,
        ]);
    }

    #[Route('/new', name: 'app_virement_international_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $virementInternational = new VirementInternational();
        $form = $this->createForm(VirementInternationalType::class, $virementInternational);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($virementInternational);
            $entityManager->flush();

            return $this->redirectToRoute('app_virement_international_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('virement_international/new.html.twig', [
            'virement_international' => $virementInternational,
            'form' => $form,
        ]);
    }

    #[Route('/{ref}', name: 'app_virement_international_show', methods: ['GET'])]
    public function show(VirementInternational $virementInternational): Response
    {
        return $this->render('virement_international/show.html.twig', [
            'virement_international' => $virementInternational,
        ]);
    }

    #[Route('/{ref}/edit', name: 'app_virement_international_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, VirementInternational $virementInternational, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VirementInternationalType::class, $virementInternational);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_virement_international_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('virement_international/edit.html.twig', [
            'virement_international' => $virementInternational,
            'form' => $form,
        ]);
    }

    #[Route('/{ref}', name: 'app_virement_international_delete', methods: ['POST'])]
    public function delete(Request $request, VirementInternational $virementInternational, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$virementInternational->getRef(), $request->request->get('_token'))) {
            $entityManager->remove($virementInternational);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_virement_international_index', [], Response::HTTP_SEE_OTHER);
    }
}
