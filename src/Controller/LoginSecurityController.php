<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;


class LoginSecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Obtenir le dernier nom d'utilisateur entré
        $lastUsername = $authenticationUtils->getLastUsername();

        try {
            // Gérer l'authentification
            $error = $authenticationUtils->getLastAuthenticationError();
            if ($error instanceof CustomUserMessageAuthenticationException) {
                // Récupérer le message d'erreur personnalisé
                $errorMessage = $error->getMessage();
            } else {
                $errorMessage = null;
            }
        } catch (\Exception $e) {
            // En cas d'erreur imprévue, définir le message d'erreur sur null
            $errorMessage = null;
        }

        // Passer l'erreur au modèle Twig s'il y en a une
        return $this->render('users/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $errorMessage, // Passer le message d'erreur personnalisé au modèle Twig
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): RedirectResponse
    {
        // Rediriger vers la page de connexion après la déconnexion
        return $this->redirectToRoute('app_login');
    }
}
