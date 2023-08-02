<?php

namespace App\Controller\Admin;

use App\Service\BookingService;
use App\Service\ClientService;
use App\Service\RealisationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/admin', name: 'app_admin_' )]
class AdminController extends AbstractController
{
    #[Route( '/', name: 'home', methods: ['GET'] )]
    public function index( ClientService $clientService, RealisationService $realisationService, BookingService $bookingService ) : Response
    {
        $dashboardCards = [
            [
                'icon' => 'users',
                'title' => 'Clients',
                'number' => count( $clientService->getClients() ),
                'route' => 'app_admin_client_index',
                'status' => 'increased',
            ],
            [
                'icon' => 'images',
                'title' => 'Réalisations',
                'number' => count( $realisationService->getRealisations() ),
                'route' => 'app_admin_realisation_index',
                'status' => 'decreased',
            ],
            [
                'icon' => 'calendar',
                'title' => 'Réservations',
                'number' => count( $bookingService->getBookings() ),
                'route' => 'app_admin_booking_index',
                'status' => 'neutral',
            ],
        ];

        return $this->render( 'admin/index.html.twig', [
            'nbClients' => count( $clientService->getClients() ),
            'nbRealisations' => count( $realisationService->getRealisations() ),
            'nbBookings' => count( $bookingService->getBookings() ),
            'dashboardCards' => $dashboardCards,
        ] );
    }


    #[Route( '/test', name: 'test', methods: ['GET'] )]
    public function testViewAdmin() : Response
    {
        return $this->render( 'admin/test.html.twig' );
    }
}
