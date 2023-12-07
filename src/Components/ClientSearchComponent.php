<?php

namespace App\Components;

use App\Repository\UserRepository;
use App\Service\ClientService;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent( 'client_search' )]
class ClientSearchComponent
{
    use DefaultActionTrait;

    #[LiveProp( writable: true )]
    public string $query = '';

    public function __construct(
        private readonly ClientService $clientService
    )
    {
    }

    public function getClients() : array
    {
        return $this->clientService->search( $this->query );
    }
}