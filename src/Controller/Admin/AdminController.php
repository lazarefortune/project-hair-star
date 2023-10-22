<?php

namespace App\Controller\Admin;

use App\Service\BookingService;
use App\Service\ClientService;
use App\Service\RealisationService;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route( '/admin', name: 'app_admin_' )]
#[IsGranted( 'ROLE_ADMIN' )]
class AdminController extends AbstractController
{
    #[Route( '/', name: 'home', methods: ['GET'] )]
    public function index( ClientService $clientService, RealisationService $realisationService, BookingService $bookingService ) : Response
    {
        $dashboardCards = [
            [
                'icon' => 'users-2',
                'title' => 'Clients',
                'number' => count( $clientService->getClients() ),
                'route' => 'app_admin_client_index',
                'status' => 'increased',
            ],
            [
                'icon' => 'scissors',
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

    #[Route( '/maintenance', name: 'maintenance', methods: ['GET'] )]
    public function maintenance() : Response
    {
        return $this->render( 'admin/layouts/maintenance.html.twig' );
    }

    #[Route( '/mails', name: 'mails', methods: ['GET'] )]
    public function notFound( BookingService $bookingService ) : Response
    {
        $bookings = $bookingService->getBookings();
        $booking = $bookings[0];
//        dd( $booking );
        return $this->render( 'mails/booking/reservation-confirmation.twig', [
            'layout' => 'mails/base.html.twig',
            'booking' => $booking,
        ] );
    }
}
