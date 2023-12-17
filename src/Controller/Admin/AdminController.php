<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Domain\Appointment\Service\AppointmentService;
use App\Service\ClientService;
use App\Service\RealisationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted( 'ROLE_ADMIN' )]
class AdminController extends AbstractController
{
    #[Route( '/dashboard', name: 'home', methods: ['GET'] )]
    public function index(
        ClientService      $clientService,
        RealisationService $realisationService,
        AppointmentService $appointmentService
    ) : Response
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
                'number' => count( $appointmentService->getAppointments() ),
                'route' => 'app_admin_appointment_index',
                'status' => 'neutral',
            ],
        ];

        return $this->render( 'admin/index.html.twig', [
            'nbClients' => count( $clientService->getClients() ),
            'nbRealisations' => count( $realisationService->getRealisations() ),
            'nbAppointments' => count( $appointmentService->getAppointments() ),
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
}
