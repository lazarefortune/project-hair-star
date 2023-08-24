<?php

namespace App\Controller\Admin;

use App\Entity\Prestation;
use App\Form\PrestationType;
use App\Repository\PrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/admin/prestations', name: 'app_admin_prestation_' )]
class PrestationController extends AbstractController
{
    #[Route( '/', name: 'index' )]
    public function index( PrestationRepository $prestationRepository ) : Response
    {
        $prestations = $prestationRepository->findAll();

        return $this->render( 'admin/prestations/index.html.twig', [
            'prestations' => $prestations,
        ] );
    }

    #[Route( '/new', name: 'new' )]
    public function new( Request $request, PrestationRepository $prestationRepository ) : Response
    {
        $prestation = new Prestation();
        $form = $this->createForm( PrestationType::class, $prestation );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $prestationRepository->save( $prestation, true );

            $this->addFlash( 'success', 'Prestation créée avec succès' );
            return $this->redirectToRoute( 'app_admin_prestation_index', [], Response::HTTP_SEE_OTHER );
        }

        return $this->render( 'admin/prestations/new.html.twig', [
            'prestation' => $prestation,
            'form' => $form,
        ] );
    }

    #[Route( '/{id}/edit', name: 'edit' )]
    public function edit( Request $request, Prestation $prestation, PrestationRepository $prestationRepository ) : Response
    {
        $form = $this->createForm( PrestationType::class, $prestation );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $prestationRepository->save( $prestation, true );

            $this->addFlash( 'success', 'Prestation modifiée avec succès' );
            return $this->redirectToRoute( 'app_admin_prestation_index', [], Response::HTTP_SEE_OTHER );
        }

        return $this->render( 'admin/prestations/edit.html.twig', [
            'prestation' => $prestation,
            'form' => $form,
        ] );
    }

    #[Route( '/{id}', name: 'delete', methods: ['POST'] )]
    public function delete( Request $request, Prestation $prestation, PrestationRepository $prestationRepository ) : Response
    {
        if ( $this->isCsrfTokenValid( 'delete' . $prestation->getId(), $request->request->get( '_token' ) ) ) {
            $prestationRepository->remove( $prestation, true );

            $this->addFlash( 'success', 'Prestation supprimée avec succès' );
        }

        return $this->redirectToRoute( 'app_admin_prestation_index', [], Response::HTTP_SEE_OTHER );
    }
}
