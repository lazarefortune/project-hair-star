<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\NewClientType;
use App\Service\ClientService;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted( 'ROLE_ADMIN' )]
#[Route( '/admin/clients', name: 'app_admin_clients_' )]
class AdminClientsController extends AbstractController
{
    #[Route( '/', name: 'index' )]
    public function index( ClientService $clientService ) : Response
    {
        $clients = $clientService->getClients();
        // remove current user from list
        $clients = array_filter( $clients, fn( $client ) => $client->getId() !== $this->getUser()->getId() );

        return $this->render( 'admin/clients/index.html.twig', [
            'clients' => $clients,
        ] );
    }

    #[Route( '/{id}/details', name: 'show' )]
    public function showClient( int $id, ClientService $clientService ) : Response
    {
        try {
            $client = $clientService->getClient( $id );
        } catch ( \Exception $e ) {
            $this->addToast( 'danger', $e->getMessage() );

            return $this->redirectToRoute( 'app_admin_clients_index' );
        }

        return $this->render( 'admin/clients/show-client.html.twig', [
            'client' => $client,
        ] );
    }

    #[Route( '/ajouter', name: 'new' )]
    public function addNewClient( Request $request, ClientService $clientService ) : Response
    {
        $form = $this->createForm( NewClientType::class );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            try {
                $clientService->addNewClient( $form->getData() );
            } catch ( \Exception $e ) {
                $this->addToast( 'danger', $e->getMessage() );

                return $this->redirectToRoute( 'app_admin_clients_new' );
            }

            $this->addToast( 'success', 'Le client a bien été ajouté' );

            return $this->redirectToRoute( 'app_admin_clients_index' );
        }

        return $this->render( 'admin/clients/new-client.html.twig', [
            'form' => $form->createView(),
        ] );

    }

    /**
     * @throws \Exception
     */
    #[Route( '/{id}/modifier', name: 'edit' )]
    public function editClient( Request $request, ClientService $clientService, int $id ) : Response
    {
        try {
            $client = $clientService->getClient( $id );
        } catch ( \Exception $e ) {
            $this->addToast( 'danger', $e->getMessage() );

            return $this->redirectToRoute( 'app_admin_clients_index' );
        }

        $form = $this->createForm( NewClientType::class, $client );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            try {
                $clientService->updateClient( $form->getData() );
            } catch ( \Exception $e ) {
                $this->addToast( 'danger', $e->getMessage() );

                return $this->redirectToRoute( 'app_admin_clients_edit', ['id' => $id] );
            }

            $this->addToast( 'success', 'Le client a bien été modifié' );

            return $this->redirectToRoute( 'app_admin_clients_show', ['id' => $id] );


//            return $this->redirectToRoute( 'app_admin_clients_index' );
        }

        return $this->render( 'admin/clients/edit-client.html.twig', [
            'form' => $form->createView(),
            'client' => $client,
        ] );
    }


    #[Route( '/{id}', name: 'delete', methods: ['POST'] )]
    public function delete( Request $request, User $client, ClientService $clientService ) : Response
    {
        if ( $this->isCsrfTokenValid( 'delete' . $client->getId(), $request->request->get( '_token' ) ) ) {
            try {
                $clientService->deleteClient( $client );
            } catch ( \Exception $e ) {
                $this->addToast( 'danger', $e->getMessage() );

                return $this->redirectToRoute( 'app_admin_clients_index' );
            }

            $this->addToast( 'success', 'Le client a bien été supprimé' );

            return $this->redirectToRoute( 'app_admin_clients_index' );
        }

        $this->addToast( 'danger', 'Le client n\'a pas pu être supprimé' );

        return $this->redirectToRoute( 'app_admin_clients_index' );
    }

}
