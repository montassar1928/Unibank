<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BannedUnbannedController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route("/banned/{id}", name: "user_banned", methods: ["POST"])]
    public function banned(int $id): Response
    {
        $user = $this->entityManager->getRepository(Users::class)->find($id);
        

        $user->setBanned('true'); // Utilisez true comme une valeur booléenne
        $this->entityManager->flush();

        return $this->redirectToRoute('app_users_list_client'); // Remplacez 'redirect_route_name' par le nom de la route vers laquelle vous souhaitez rediriger
    }

    #[Route("/unbanned/{id}", name: "user_unbanned", methods: ["POST"])]
    public function unbanned(int $id): Response
    {
        $user = $this->entityManager->getRepository(Users::class)->find($id);
 

        $user->setBanned('false'); // Utilisez false comme une valeur booléenne
        $this->entityManager->flush();

        return $this->redirectToRoute('app_users_list_client'); // Remplacez 'redirect_route_name' par le nom de la route vers laquelle vous souhaitez rediriger
    }
    #[Route("/bannedbanque/{id}", name: "banque_banned", methods: ["POST"])]
    public function bannedbanque(int $id): Response
    {
        $user = $this->entityManager->getRepository(Users::class)->find($id);
        

        $user->setBanned('true'); // Utilisez true comme une valeur booléenne
        $this->entityManager->flush();

        return $this->redirectToRoute('app_banque'); // Remplacez 'redirect_route_name' par le nom de la route vers laquelle vous souhaitez rediriger
    }

    #[Route("/unbannedbanque/{id}", name: "banque_unbanned", methods: ["POST"])]
    public function unbannedbanque(int $id): Response
    {
        $user = $this->entityManager->getRepository(Users::class)->find($id);
 

        $user->setBanned('false'); // Utilisez false comme une valeur booléenne
        $this->entityManager->flush();

        return $this->redirectToRoute('app_banque'); // Remplacez 'redirect_route_name' par le nom de la route vers laquelle vous souhaitez rediriger
    }
}
