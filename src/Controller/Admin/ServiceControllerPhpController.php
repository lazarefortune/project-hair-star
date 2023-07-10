<?php

namespace App\Controller\Admin;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/admin/prestations', name: 'app_admin_service_' )]
class ServiceControllerPhpController extends AbstractController
{
    #[Route( '/', name: 'index' )]
    public function index( ServiceRepository $serviceRepository ) : Response
    {
        $services = $serviceRepository->findAll();

        return $this->render( 'admin/services/index.html.twig', [
            'services' => $services,
        ] );
    }
}
