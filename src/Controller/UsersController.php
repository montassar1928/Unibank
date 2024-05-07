<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use Symfony\Component\Form\FormError;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
#[Route('/users')]
class UsersController extends AbstractController
{
    #[Route('/', name: 'app_users_index', methods: ['GET'])]
   // Dans votre contrôleur
public function index(Request $request, UsersRepository $usersRepository, PaginatorInterface $paginator): Response
{
    $query = $request->query->get('query');
    $filter = $request->query->get('filter');

    // Récupérer les utilisateurs inactifs
    $queryBuilder = $usersRepository->createQueryBuilder('u')
        ->where('u.statut = :statut')
        ->andWhere('u.role = :role')
        ->setParameter('statut', 'inactif')
        ->setParameter('role', 'CLIENT');

    // Ajouter une condition de recherche si un terme de recherche est fourni
    if ($query) {
        if ($filter === 'nom') {
            $queryBuilder->andWhere('u.nom LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        } elseif ($filter === 'prenom') {
            $queryBuilder->andWhere('u.prenom LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        } elseif ($filter === 'cin') {
            $queryBuilder->andWhere('u.cin LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }
    }

    // Paginer les résultats de la requête
    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(), // Requête à paginer
        $request->query->getInt('page', 1), // Numéro de page
        10 // Nombre d'éléments par page
    );

    return $this->render('users/index.html.twig', [
        'pagination' => $pagination,
    ]);
}
#[Route('/new', name: 'app_users_new', methods: ['GET', 'POST'])]
public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
{
    $user = new Users();
    $form = $this->createForm(UsersType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user->setDateCreation(new \DateTime());

        // Hacher le mot de passe
        $plainPassword = $user->getPassword();
        $hashedPassword = $passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        // Gérer le téléchargement de la photo
        $photoFile = $form->get('photo')->getData();

        if ($photoFile instanceof UploadedFile) {
            // Récupérer le nom d'origine du fichier
            $originalFileName = $photoFile->getClientOriginalName();
        
            // Déplacer le fichier téléchargé vers le répertoire de destination en utilisant le nom d'origine
            try {
                $photoFile->move($this->getParameter('kernel.project_dir') . '/public/img', $originalFileName);
        
                // Enregistrer le nom du fichier dans l'entité Users
                $user->setPhoto($originalFileName);
            } catch (FileException $e) {
                // Gérer l'exception si le déplacement du fichier échoue
                // Par exemple, afficher un message d'erreur ou enregistrer les détails de l'erreur dans les logs
                // Vous pouvez ajouter ici le code pour gérer l'erreur selon vos besoins
            }
        }
        

        // Persistez les modifications de l'utilisateur dans la base de données
        $entityManager->persist($user);
        $entityManager->flush();

        // Rediriger l'utilisateur vers une page appropriée
    }

    return $this->renderForm('users/new.html.twig', [
        'user' => $user,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_users_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
    }
    

    #[Route('/{id}/edit', name: 'app_users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
        }
    
        return $this->renderForm('users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    

    #[Route('/list-client', name: 'app_users_list_client', methods: ['GET'])]
    public function listClient(Request $request, UsersRepository $usersRepository, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('query');
        $filter = $request->query->get('filter');
        
        $queryBuilder = $usersRepository->createQueryBuilder('u')
            ->where('u.statut = :statut')
            ->andWhere('u.role = :role')
            ->setParameter('statut', 'Actif')
            ->setParameter('role', 'CLIENT');
        
        if ($query) {
            if ($filter === 'nom') {
                $queryBuilder->andWhere('u.nom LIKE :query')
                    ->setParameter('query', '%' . $query . '%');
            } elseif ($filter === 'prenom') {
                $queryBuilder->andWhere('u.prenom LIKE :query')
                    ->setParameter('query', '%' . $query . '%');
            } elseif ($filter === 'cin') {
                $queryBuilder->andWhere('u.cin LIKE :query')
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
   
    
}
