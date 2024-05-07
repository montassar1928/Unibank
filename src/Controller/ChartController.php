<?php
// src/Controller/ChartController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsersRepository;

class ChartController extends AbstractController
{
    #[Route("/compare-active-inactive-users", name: "compare_active_inactive_userss", methods: ["GET"])]
     public function compareActiveInactiveUsers(UsersRepository $userRepository): Response
    {
        $activeUsersCount = $userRepository->countUsersByStatut('Actif');
        $inactiveUsersCount = $userRepository->countUsersByStatut('inactif');

        return $this->render('Dashboard/dashboard.html.twig', [
            'active_users' => $activeUsersCount,
            'inactive_users' => $inactiveUsersCount,
        ]);
    }
}
