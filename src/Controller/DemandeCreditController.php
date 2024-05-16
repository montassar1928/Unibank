<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;




#[Route('/credit')]
class DemandeCreditController extends AbstractController
{
    #[Route('/', name: 'app_demande_credit_index', methods: ['GET'])]
    public function index(DemandeRepository $demandeRepository, Security $security): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $security->getUser();
    
        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to view this page.');
        }
    
        // Récupérer les demandes de crédit de l'utilisateur actuel
        $allRequests = $demandeRepository->findBy(['iduser' => $user->getId()], ['date' => 'DESC']);
    
        return $this->render('credit/base.html.twig', [
            'allRequests' => $allRequests
        ]);
    }
    #[Route('/new', name: 'app_demande_credit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,Security $security): Response
    {
        $demande = new Demande();
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);
        $user = $security->getUser();


        if ($form->isSubmitted() && $form->isValid()) {
            $demande->setDate(new \DateTime());
            $demande->setIduser($user);

            $entityManager->persist($demande);
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_credit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('credit/new.html.twig', [
            'demande' => $demande,
            'form' => $form,
        ]);
    }

    #[Route('/demandeshow', name: 'app_demande_credit_showw', methods: ['GET'])]
public function showw(DemandeRepository $demandeRepository): Response
{
    $demandes = $demandeRepository->findAll();

    return $this->render('reponse_controller_php/show.html.twig', [
        'demandes' => $demandes,
    ]);
}

    #[Route('/demandeshow', name: 'app_demande_credit_show', methods: ['GET'])]
    public function show(Demande $demande): Response
    {
        return $this->render('reponse_controller_php/show.html.twig', [
            'demande' => $demande,
        ]);
    }
    

    #[Route('/{id}/edit', name: 'app_demande_credit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Demande $demande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_credit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande_credit/edit.html.twig', [
            'demande' => $demande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_credit_delete', methods: ['POST'])]
    public function delete(Request $request, Demande $demande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $demande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($demande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demande_credit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/list-demande', name: 'app_demandes_list', methods: ['GET'])]
    public function listDemande(Request $request, DemandeRepository $DemandeRepository, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('query');
        $filter = $request->query->get('filter');

        $queryBuilder = $DemandeRepository->createQueryBuilder('u')
            ->where('u.statut = :statut')
            ->setParameter('statut', 'traité');

        if ($query) {
            if ($filter === 'montant') {
                $queryBuilder->andWhere('u.montant LIKE :query')
                    ->setParameter('query', '%' . $query . '%');
            } elseif ($filter === 'revenu') {
                $queryBuilder->andWhere('u.revenu LIKE :query')
                    ->setParameter('query', '%' . $query . '%');
            } elseif ($filter === 'duree') {
                $queryBuilder->andWhere('u.duree LIKE :query')
                    ->setParameter('query', '%' . $query . '%');
            }
        }

        // Utilisation de $queryBuilder pour la pagination
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(), // Requête à paginer
            $request->query->getInt('page', 1), // Numéro de page
            10 // Nombre d'éléments par page
        );

        return $this->render('demande_credit/show.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    #[Route('/demande/{id}/traiter', name: 'app_demande_traiter', methods: ['GET'])]
public function traiter(Demande $demande, EntityManagerInterface $entityManager): RedirectResponse
{
    $demande->setStatut('traité');
    $entityManager->flush();

    return $this->redirectToRoute('app_demande_credit_showw');
}

#[Route('/demande/{id}/non-traiter', name: 'app_demande_non_traiter', methods: ['GET'])]
public function nonTraiter(Demande $demande, EntityManagerInterface $entityManager): RedirectResponse
{
    $demande->setStatut('non traité');
    $entityManager->flush();

    return $this->redirectToRoute('app_demande_credit_showw');
}
}
