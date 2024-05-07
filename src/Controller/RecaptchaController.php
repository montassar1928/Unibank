<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RecaptchaController extends AbstractController
{
    #[Route("/validate-recaptcha", name: "validate_recaptcha", methods: ["POST"])]
    public function validateRecaptcha(Request $request): JsonResponse
    {
        // Récupérer le jeton reCAPTCHA envoyé par le client
        $recaptchaToken = $request->request->get('token');

        // Valider le jeton reCAPTCHA
        $isValid = $this->validateRecaptchaToken($recaptchaToken);

        // Retourner une réponse JSON indiquant si la validation a réussi ou échoué
        return new JsonResponse(['success' => $isValid]);
    }

    // Méthode de validation reCAPTCHA
    private function validateRecaptchaToken(string $recaptchaToken): bool
    {
        // Clé secrète reCAPTCHA
        $secretKey = '6LewIrYpAAAAALp5rHL0Uj3sXE2OtWcyxuMDC3Rl'; // Votre clé secrète reCAPTCHA

        // URL de l'API de validation reCAPTCHA
        $url = 'https://www.google.com/recaptcha/api/siteverify';

        // Données à envoyer à l'API de validation
        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaToken
        ];

        // Configuration de la requête HTTP
        $options = [
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        // Créer un contexte de flux
        $context = stream_context_create($options);

        // Envoyer la demande à l'API de validation reCAPTCHA
        $response = file_get_contents($url, false, $context);

        // Analyser la réponse JSON
        $responseData = json_decode($response, true);

        // Vérifier si la validation a réussi
        return isset($responseData['success']) && $responseData['success'] === true;
    }
}
