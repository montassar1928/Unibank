<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/listbanque', name: 'app_banque', methods: ['GET'])]
    public function index(Request $request, UsersRepository $usersRepository, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('query');
        $filter = $request->query->get('filter');

        // Récupérer les utilisateurs inactifs
        $queryBuilder = $usersRepository->createQueryBuilder('u')
            ->where('u.role = :role')
            ->setParameter('role', 'BANQUE');

        // Ajouter une condition de recherche si un terme de recherche est fourni
        if ($query) {
            if ($filter === 'nom') {
                $queryBuilder->andWhere('u.nom LIKE :query')
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

        return $this->render('admin/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_new_banque', methods: ['GET', 'POST'])]
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
                // Générer un nom de fichier unique
                $originalFileName = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFileName = $originalFileName.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/img',
                        $newFileName
                    );

                    // Enregistrer le nom du fichier dans l'entité Users
                    $user->setPhoto($newFileName);
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
            return $this->redirectToRoute('app_banque');
        }

        return $this->render('admin/newbanque.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_users1_controllers_show', methods: ['GET'])]
    public function show(Users $user): Response
    {
        // Affiche les détails d'un utilisateur
        return $this->render('users1_controllers/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_users1_controllers_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        // Édite un utilisateur existant
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        // Soumet le formulaire et met à jour l'utilisateur
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_banque');
        }

        // Affiche le formulaire d'édition d'utilisateur
        return $this->render('users1_controllers/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_users1_controllers_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        // Supprime un utilisateur existant
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_banque');
    }
}
