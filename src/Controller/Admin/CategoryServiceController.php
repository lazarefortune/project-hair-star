<?php

namespace App\Controller\Admin;

use App\Entity\CategoryService;
use App\Form\CategoryServiceType;
use App\Repository\CategoryServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/admin/category/service' )]
class CategoryServiceController extends AbstractController
{
    #[Route( '/', name: 'app_admin_category_service_index', methods: ['GET'] )]
    public function index( CategoryServiceRepository $categoryServiceRepository ) : Response
    {
        return $this->render( 'admin/category_service/index.html.twig', [
            'category_services' => $categoryServiceRepository->findAll(),
        ] );
    }

    #[Route( '/new', name: 'app_admin_category_service_new', methods: ['GET', 'POST'] )]
    public function new( Request $request, CategoryServiceRepository $categoryServiceRepository ) : Response
    {
        $categoryService = new CategoryService();
        $form = $this->createForm( CategoryServiceType::class, $categoryService );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $categoryServiceRepository->save( $categoryService, true );

            $this->addFlash( 'success', 'Catégorie de prestation créée avec succès' );
            return $this->redirectToRoute( 'app_admin_category_service_index', [], Response::HTTP_SEE_OTHER );
        }

        return $this->renderForm( 'admin/category_service/new.html.twig', [
            'category_service' => $categoryService,
            'form' => $form,
        ] );
    }

    #[Route( '/{id}', name: 'app_admin_category_service_show', methods: ['GET'] )]
    public function show( CategoryService $categoryService ) : Response
    {
        return $this->render( 'admin/category_service/show.html.twig', [
            'category_service' => $categoryService,
        ] );
    }

    #[Route( '/{id}/edit', name: 'app_admin_category_service_edit', methods: ['GET', 'POST'] )]
    public function edit( Request $request, CategoryService $categoryService, CategoryServiceRepository $categoryServiceRepository ) : Response
    {
        $form = $this->createForm( CategoryServiceType::class, $categoryService );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $categoryServiceRepository->save( $categoryService, true );

            $this->addFlash( 'success', 'Catégorie de prestation modifiée avec succès' );
            return $this->redirectToRoute( 'app_admin_category_service_index', [], Response::HTTP_SEE_OTHER );
        }

        return $this->renderForm( 'admin/category_service/edit.html.twig', [
            'category_service' => $categoryService,
            'form' => $form,
        ] );
    }

    #[Route( '/{id}', name: 'app_admin_category_service_delete', methods: ['POST'] )]
    public function delete( Request $request, CategoryService $categoryService, CategoryServiceRepository $categoryServiceRepository ) : Response
    {
        if ( $this->isCsrfTokenValid( 'delete' . $categoryService->getId(), $request->request->get( '_token' ) ) ) {
            $this->addFlash( 'success', 'Catégorie de prestation supprimée avec succès' );
            $categoryServiceRepository->remove( $categoryService, true );
        }

        return $this->redirectToRoute( 'app_admin_category_service_index', [], Response::HTTP_SEE_OTHER );
    }
}
