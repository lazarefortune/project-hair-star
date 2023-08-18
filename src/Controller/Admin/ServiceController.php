<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/admin/prestations', name: 'app_admin_service_' )]
class ServiceController extends AbstractController
{
    #[Route( '/', name: 'index' )]
    public function index( ServiceRepository $serviceRepository ) : Response
    {
        $services = $serviceRepository->findAll();

        return $this->render( 'admin/services/index.html.twig', [
            'services' => $services,
        ] );
    }

    #[Route( '/new', name: 'new' )]
    public function new( Request $request, ServiceRepository $serviceRepository ) : Response
    {
        $service = new Service();
        $form = $this->createForm( ServiceType::class, $service );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $serviceRepository->save( $service, true );

            return $this->redirectToRoute( 'app_admin_service_index', [], Response::HTTP_SEE_OTHER );
        }

        return $this->render( 'admin/services/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ] );
    }

    #[Route( '/{id}/edit', name: 'edit' )]
    public function edit( Request $request, Service $service, ServiceRepository $serviceRepository ) : Response
    {
        $form = $this->createForm( ServiceType::class, $service );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $serviceRepository->save( $service, true );

            return $this->redirectToRoute( 'app_admin_service_index', [], Response::HTTP_SEE_OTHER );
        }

        return $this->render( 'admin/services/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ] );
    }
}
