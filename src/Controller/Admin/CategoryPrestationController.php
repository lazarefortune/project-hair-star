<?php

namespace App\Controller\Admin;

use App\Entity\CategoryPrestation;
use App\Form\CategoryPrestationType;
use App\Repository\CategoryPrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route( '/admin/category/prestations' )]
#[IsGranted( 'ROLE_ADMIN' )]
class CategoryPrestationController extends AbstractController
{
    #[Route( '/', name: 'app_admin_category_prestation_index', methods: ['GET'] )]
    public function index( CategoryPrestationRepository $categoryPrestationRepository ) : Response
    {
        return $this->render( 'admin/category-prestation/index.html.twig', [
            'category_prestations' => $categoryPrestationRepository->findAll(),
        ] );
    }

    #[Route( '/new', name: 'app_admin_category_prestation_new', methods: ['GET', 'POST'] )]
    public function new( Request $request, CategoryPrestationRepository $categoryPrestationRepository ) : Response
    {
        $categoryPrestation = new CategoryPrestation();
        $form = $this->createForm( CategoryPrestationType::class, $categoryPrestation );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $categoryPrestationRepository->save( $categoryPrestation, true );

            $this->addFlash( 'success', 'Catégorie de prestation créée avec succès' );
            return $this->redirectToRoute( 'app_admin_category_prestation_index', [], Response::HTTP_SEE_OTHER );
        }

        return $this->renderForm( 'admin/category-prestation/new.html.twig', [
            'category_service' => $categoryPrestation,
            'form' => $form,
        ] );
    }

    #[Route( '/{id}', name: 'app_admin_category_service_show', methods: ['GET'] )]
    public function show( CategoryPrestation $categoryPrestation ) : Response
    {
        return $this->render( 'admin/category-prestation/show.html.twig', [
            'category_service' => $categoryPrestation,
        ] );
    }

    #[Route( '/{id}/edit', name: 'app_admin_category_service_edit', methods: ['GET', 'POST'] )]
    public function edit( Request $request, CategoryPrestation $categoryPrestation, CategoryPrestationRepository $categoryPrestationRepository ) : Response
    {
        $form = $this->createForm( CategoryPrestationType::class, $categoryPrestation );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $categoryPrestationRepository->save( $categoryPrestation, true );

            $this->addFlash( 'success', 'Catégorie de prestation modifiée avec succès' );
            return $this->redirectToRoute( 'app_admin_category_prestation_index', [], Response::HTTP_SEE_OTHER );
        }

        return $this->renderForm( 'admin/category-prestation/edit.html.twig', [
            'category_service' => $categoryPrestation,
            'form' => $form,
        ] );
    }

    #[Route( '/{id}', name: 'app_admin_category_prestation_delete', methods: ['POST'] )]
    public function delete( Request $request, CategoryPrestation $categoryPrestation, CategoryPrestationRepository $categoryPrestationRepository ) : Response
    {
        if ( $this->isCsrfTokenValid( 'delete' . $categoryPrestation->getId(), $request->request->get( '_token' ) ) ) {
            $this->addFlash( 'success', 'Catégorie de prestation supprimée avec succès' );
            $categoryPrestationRepository->remove( $categoryPrestation, true );
        }

        return $this->redirectToRoute( 'app_admin_category_prestation_index', [], Response::HTTP_SEE_OTHER );
    }
}
