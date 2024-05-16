<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\DemandeRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Options;
use Dompdf\Dompdf;
use Symfony\Component\DependencyInjection\ContainerInterface;
#[Route('/reponse')]
class ReponseCreditController extends AbstractController
{
    #[Route('/', name: 'app_reponse_credit_index', methods: ['GET'])]
    public function index(ManagerRegistry $mr): Response
    {
        $allRequests = $mr->getRepository(Reponse::class)->findBy([], ['date_r' => 'DESC']);
        return $this->render('reponse_controller_php/show.html.twig', [
        'allRequests'=>$allRequests
        ]);
    }

    #[Route('/new', name: 'app_reponse_credit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->isdate_r(new \DateTime());} 
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponse);
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_credit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse_controller_php/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{idR}', name: 'app_reponse_credit_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse_credit/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    #[Route('/{idR}/edit', name: 'app_reponse_credit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_credit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse_credit/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{idR}', name: 'app_reponse_credit_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponse_credit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/list-reponse', name: 'app_reponse_list', methods: ['GET'])]
    public function listClient(Request $request, ReponseRepository $ReponseRepository, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('query');
        $filter = $request->query->get('filter');

        $queryBuilder = $ReponseRepository->createQueryBuilder('u')
            ->where('u.statutR = :statutR')
            ->setParameter('statutR', 'traité');

        if ($query) {
            if ($filter === 'montantR') {
                $queryBuilder->andWhere('u.montantR LIKE :query')
                    ->setParameter('query', '%' . $query . '%');
            } elseif ($filter === 'dureeR') {
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

        return $this->render('users/listClient.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    /**
 * @Route("/reponse/data/download", name="reponse_data_download")
 */
public function ReponseDataDownload(ManagerRegistry $mr, ContainerInterface $container)
{
    // Récupérer les données nécessaires depuis la base de données
    $allRequests = $mr->getRepository(Reponse::class)->findBy([], ['date_r' => 'DESC']);

    // On définit les options du PDF
    $pdfOptions = new Options();
    // Police par défaut
    $pdfOptions->set('defaultFont', 'Arial');
    $pdfOptions->setIsRemoteEnabled(true);

    // On instancie Dompdf
    $dompdf = new Dompdf($pdfOptions);
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => FALSE,
            'verify_peer_name' => FALSE,
            'allow_self_signed' => TRUE
        ]
    ]);
    $dompdf->setHttpContext($context);

    // On génère le html en passant les données à la vue Twig
    $html = $container->get('twig')->render('reponse_controller_php/download.html.twig', [
        'allRequests' => $allRequests
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // On génère un nom de fichier
    $fichier = 'Response-Data'.'.pdf';

    // On envoie le PDF au navigateur
    $dompdf->stream($fichier, [
        'Attachment' => true
    ]);

    return new Response();
}
}
