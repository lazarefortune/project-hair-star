<?php

namespace App\Controller\Admin;

use App\Repository\RealisationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index( UserRepository $userRepository, RealisationRepository $realisationRepository ): Response
    {
        $allUsers = $userRepository->findBy(
            [],
            [ 'createdAt' => 'DESC' ]
        );
        $nbUsers = count( $allUsers );

        $allRealisations = $realisationRepository->findBy(
            [],
            [
                'createdAt' => 'DESC',
                'dateRealisation' => 'DESC'
            ]
        );
        $nbRealisations = count( $allRealisations );

        return $this->render('admin/index.html.twig', [
            'nbUsers' => $nbUsers,
            'nbRealisations' => $nbRealisations
        ]);
    }
}
