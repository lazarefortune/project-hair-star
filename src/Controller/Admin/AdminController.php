<?php

namespace App\Controller\Admin;

use App\Repository\RealisationRepository;
use App\Repository\UserRepository;
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
        return $this->render( 'admin/index.html.twig', [
            'nbClients' => count( $clientService->getClients() ),
            'nbRealisations' => count( $realisationService->getRealisations() ),
            'nbBookings' => count( $bookingService->getBookings() ),
        ] );
    }
}
