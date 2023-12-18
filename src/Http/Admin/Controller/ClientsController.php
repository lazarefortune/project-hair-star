<?php

namespace App\Http\Admin\Controller;

use App\Domain\Auth\Entity\User;
use App\Domain\Auth\Event\UserCreatedEvent;
use App\Domain\Client\Event\DeleteClientEvent;
use App\Domain\Client\Service\ClientService;
use App\Http\Admin\Data\Crud\ClientCrudData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted( 'ROLE_ADMIN' )]
#[Route( '/clients', name: 'clients_' )]
class ClientsController extends CrudController
{

    protected string $templatePath = 'clients';
    protected string $menuItem = 'client';
    protected string $searchField = 'name';
    protected string $entity = User::class;
    protected string $routePrefix = 'app_admin_clients';
    protected array $events = [
        'create' => UserCreatedEvent::class,
        'delete' => DeleteClientEvent::class
    ];

    #[Route( path: '/', name: 'index', methods: ['GET'] )]
    public function index() : Response
    {
        $queryBuilder = $this->getRepository()->createQueryBuilder( 'row' );
        $queryBuilder->where( 'row.roles LIKE :role' )
            ->setParameter( 'role', '%"ROLE_CLIENT"%' );

        // remove current user from list
        /** @var User $user */
        $user = $this->getUser();
        $queryBuilder->andWhere( 'row.id != :id' )
            ->setParameter( 'id', $user->getId() )
            ->orderBy( 'row.createdAt', 'DESC' )
            ->setMaxResults( 5 );

        return parent::crudIndex( $queryBuilder );
    }

    #[Route( path: '/new', name: 'new', methods: ['POST', 'GET'] )]
    public function new() : Response
    {
        $client = new User();
        $data = new ClientCrudData( $client );
        return $this->crudNew( $data );
    }

    #[Route( path: '/{id<\d+>}', name: 'edit', methods: ['POST', 'GET'] )]
    public function edit( User $client ) : Response
    {
        $data = new ClientCrudData( $client );

        return $this->crudEdit( $data );
    }

    #[Route( path: '/{id<\d+>}', methods: ['DELETE'] )]
    public function delete( User $client ) : Response
    {
        return $this->crudDelete( $client );
    }

    /**
     * @throws \Exception
     */
    #[Route( path: '/{id<\d+>}/show', name: 'show', methods: ['GET'] )]
    public function show( User $client, ClientService $clientService ) : Response
    {
        $client = $clientService->getClient( $client->getId() );
        $clientAppointments = $clientService->getClientAppointments( $client, 4 );


        return $this->render( 'admin/clients/show-client.html.twig', [
            'client' => $client,
            'clientAppointments' => $clientAppointments,
            'EMAILS_LOG_LIMIT' => 4,
        ] );
    }

    #[Route( path: '/search', name: 'search', methods: ['GET'] )]
    public function search( Request $request ) : Response
    {
        return $this->render( 'admin/clients/_search.html.twig', [
            'query' => (string)$request->query->get( 'query', '' ),
        ] );
    }

//    #[Route( '/{id<\d+>}/details', name: 'show', methods: ['GET'] )]
//    public function showClient( int $id ) : Response
//    {
//        $EMAILS_LOG_LIMIT = 4;
//
//        try {
//            $client = $this->clientService->getClient( $id );
//            $clientEmailsLogs = $this->clientService->getClientMailsLog( $client, $EMAILS_LOG_LIMIT );
//        } catch ( \Exception $e ) {
//            $this->addToast( 'danger', $e->getMessage() );
//
//            return $this->redirectToRoute( 'app_admin_clients_index' );
//        }
//
//        return $this->render( 'admin/clients/show-client.html.twig', [
//            'client' => $client,
//            'clientEmailsLogs' => $clientEmailsLogs,
//            'EMAILS_LOG_LIMIT' => $EMAILS_LOG_LIMIT,
//        ] );
//    }

}
