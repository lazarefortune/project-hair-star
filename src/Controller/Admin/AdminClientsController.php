<?php

namespace App\Controller\Admin;

use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\ClientService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route( '/admin/clients', name: 'app_admin_clients_' )]
class AdminClientsController extends AbstractController
{
    #[Route( '/', name: 'index' )]
    public function index( ClientService $clientService ) : Response
    {
        $clients = $clientService->getClients();

        return $this->render( 'admin/clients/index.html.twig', [
            'clients' => $clients,
        ] );
    }

    #[Route( '/ajouter', name: 'new' )]
    public function addNewClient( Request $request, ClientService $clientService ) : Response
    {
        $form = $this->createForm( UserType::class );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            try {
                $clientService->addNewClient( $form->getData() );
            } catch ( \Exception $e ) {
                $this->addFlash( 'danger', $e->getMessage() );

                return $this->redirectToRoute( 'app_admin_clients_new' );
            }

            $this->addFlash( 'success', 'Le client a bien été ajouté' );

            return $this->redirectToRoute( 'app_admin_clients_index' );
        }

        return $this->render( 'admin/clients/add.html.twig', [
            'form' => $form->createView(),
        ] );

    }
}
