<?php

namespace App\Http\Admin\Controller;

use App\Domain\Appointment\Entity\Appointment;
use App\Domain\Appointment\Service\AppointmentService;
use App\Domain\Client\Service\ClientService;
use App\Domain\Payment\Service\StripePayment;
use App\Domain\Realisation\Service\RealisationService;
use App\Http\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted( 'ROLE_ADMIN' )]
class PageController extends AbstractController
{

    public function __construct(
        private readonly StripePayment $stripePayment
    )
    {
    }

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
        $paymentUrl = "";
        return $this->render( 'admin/test.html.twig', [
                'paymentUrl' => $paymentUrl,
            ]
        );
    }

    #[Route( '/maintenance', name: 'maintenance', methods: ['GET'] )]
    public function maintenance() : Response
    {
        return $this->render( 'admin/layouts/maintenance.html.twig' );
    }
}
