<?php

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Entity\Option;
use App\Service\HolidayService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiOffDaysController extends AbstractController
{
    public function __construct( private readonly EntityManagerInterface $entityManager )
    {
    }

    #[Route( '/api/off-days', name: 'api_off_days_' )]
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