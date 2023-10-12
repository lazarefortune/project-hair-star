<?php

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Entity\Prestation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiPrestationsController extends AbstractController
{
    public function __construct( private readonly EntityManagerInterface $entityManager )
    {
    }

    #[Route( '/api/prestations/{id}', name: 'api_prestations_' )]
    public function index( Prestation $prestation ) : JsonResponse
    {
        $prestations = $this->entityManager->getRepository( Prestation::class )->findBy( ['id' => $prestation->getId()] );

        $apiPrestations = [];
        foreach ( $prestations as $prestation ) {
            $apiPrestations = [
                'id' => $prestation->getId(),
                'dateStart' => $prestation->getStartDate()->format( 'Y-m-d' ),
                'dateEnd' => $prestation->getEndDate()->format( 'Y-m-d' ),
                'timeStart' => $prestation->getStartTime()->format( 'H:i' ),
                'timeEnd' => $prestation->getEndTime()->format( 'H:i' ),
            ];
        }

        return new JsonResponse( $apiPrestations );
    }
}