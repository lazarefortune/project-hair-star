<?php

namespace App\Http\Api\Controller;

use App\Domain\Application\Entity\Option;
use App\Http\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiOffDaysController extends AbstractController
{
    public function __construct( private readonly EntityManagerInterface $entityManager )
    {
    }

    #[Route( '/off-days', name: 'off_days_' )]
    public function index() : JsonResponse
    {
        $offDaysInEachWeek = $this->entityManager->getRepository( Option::class )->findOneBy( ['name' => 'offdays'] );

        if ( !$offDaysInEachWeek ) {
            return new JsonResponse( [] );
        }
        $values = $offDaysInEachWeek->getValue();
        if ( $values ) {
            $offDaysArray = explode( ',', $offDaysInEachWeek->getValue() );
        } else {
            $offDaysArray = [];
        }

        return new JsonResponse( $offDaysArray );
    }
}