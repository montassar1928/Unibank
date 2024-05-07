<?php

namespace App\Security;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class LoginUsersAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $urlGenerator;
    private $entityManager;
    private $passwordEncoder;
    private $session;

    public function __construct(UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, SessionInterface $session)
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->session = $session; // Injection de dépendance pour la session
    }

    public function authenticate(Request $request): Passport
{
    $email = $request->request->get('email', '');
    $password = $request->request->get('password', '');

    $request->getSession()->set(Security::LAST_USERNAME, $email);

    $user = $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $email]);

    // Vérifier si l'utilisateur existe
    if (!$user) {
        throw new CustomUserMessageAuthenticationException('Email ou mot de passe incorrect.');
    }

    // Vérifier si le mot de passe est correct
    if (!password_verify($password, $user->getPassword())) {
        throw new CustomUserMessageAuthenticationException('Email ou mot de passe incorrect.');
    }

    // Vérifier si l'utilisateur est actif
    if ($user->getStatut() === 'inactif') {
        throw new CustomUserMessageAuthenticationException('Votre compte est inactif pour le moment.');
    }

    // Vérifier si l'utilisateur est banni
    if ($user->getBanned() === 'true') {
        throw new CustomUserMessageAuthenticationException('Votre compte a été bloqué par l\'administrateur.');
    }

    return new Passport(
        new UserBadge($email, function ($email) {
            // Charger l'entité utilisateur par email
            return $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $email]);
        }),
        new PasswordCredentials($password),
        [
            new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            new RememberMeBadge(),
        ]
    );
}


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Check if the target path exists
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Retrieve the user object from the token
        $user = $token->getUser();

        // If user is active and not banned, proceed with login based on user role
        $role = $user->getRole();
        if ($role === "CLIENT") {
            return new RedirectResponse($this->urlGenerator->generate('app_profile'));
        }
        if ($role === "BANQUE") {
            return new RedirectResponse($this->urlGenerator->generate('app_users_index'));
        }

        return new RedirectResponse($this->urlGenerator->generate('app_banque'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}

