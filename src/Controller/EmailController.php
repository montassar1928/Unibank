<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EmailController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/validate-email', name: 'validate_email', methods: ['POST'])]
    public function validateEmail(Request $request, MailerInterface $mailer): Response
    {
        $email = $request->request->get('email');

        $userRepository = $this->entityManager->getRepository(Users::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        if ($user instanceof Users) {
            // L'email est valide, générer un code de confirmation
            $confirmationCode = substr(md5(uniqid(rand(), true)), 0, 6);

            // Enregistrer le code de confirmation dans la session
            $request->getSession()->set('confirmation_code', $confirmationCode);
            // Enregistrer l'email dans la session pour la réinitialisation du mot de passe
            $request->getSession()->set('reset_email', $email);

            // Envoyer le code de confirmation par e-mail
            $this->sendConfirmationEmail($email, $confirmationCode, $mailer);

            return new Response('Confirmation code sent to the email address.');
        } else {
            return new Response('Invalid email address.', 400);
        }
    }

    private function sendConfirmationEmail(string $email, string $confirmationCode, MailerInterface $mailer): void
    {
        $message = "Dear user,\n\nPlease use the following confirmation code to proceed with your action: $confirmationCode.\n\nIf you didn't request this confirmation code, please ignore this email.\n\nBest regards,\nThe [Your Company Name] Team";

        $email = (new Email())
            ->from('montaazzouz2@gmail.com')
            ->to($email)
            ->subject('Confirmation Code')
            ->text(  $message);

        $mailer->send($email);
    }

    #[Route('/confirm-code', name: 'confirm_code', methods: ['POST'])]
    public function confirmCode(Request $request): Response
    {
        $enteredCode = $request->request->get('confirmation-code');
        $storedCode = $request->getSession()->get('confirmation_code');

        if ($enteredCode === $storedCode) {
            // Code de confirmation correct, afficher une popup de réinitialisation du mot de passe
            return new Response('Correct confirmation code.');
        } else {
            // Code de confirmation incorrect, afficher une erreur
            return new Response('Incorrect confirmation code.', 400);
        }
    }

    #[Route('/reset-password', name: 'reset_password', methods: ['POST'])]
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // Récupérer l'e-mail de réinitialisation depuis la session
        $email = $request->getSession()->get('reset_email');

        // Récupérer l'utilisateur correspondant à l'e-mail depuis la base de données
        $userRepository = $this->entityManager->getRepository(Users::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        // Vérifier si l'utilisateur existe
        if ($user instanceof Users) {
            // Récupérer le nouveau mot de passe depuis le formulaire
            $newPassword = $request->request->get('new-password');
            $confirmNewPassword = $request->request->get('confirm-new-password');

            // Vérifier si les mots de passe correspondent
            if ($newPassword !== $confirmNewPassword) {
                return new Response('Passwords do not match.', 400);
            }

            // Encoder le nouveau mot de passe avec bcrypt
            $hashedPassword = $passwordEncoder->encodePassword($user, $newPassword);

            // Mettre à jour le mot de passe de l'utilisateur
            $user->setPassword($hashedPassword);

            // Supprimer l'email de réinitialisation de la session
            $request->getSession()->remove('reset_email');

            // Enregistrer les modifications dans la base de données
            $this->entityManager->flush();

            // Retourner une réponse indiquant que le mot de passe a été réinitialisé avec succès
            return new Response('Password reset successfully.');
        } else {
            // L'utilisateur avec cet e-mail n'existe pas, retourner une erreur
            return new Response('User not found.', 404);
        }
    }
}

