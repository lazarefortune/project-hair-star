<?php

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Service\HolidayService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/holidays', name: 'holidays_' )]
class ApiHolidaysController extends AbstractController
{
    public function __construct( private readonly HolidayService $holidayService )
    {
    }

    #[Route( '/', name: 'index', methods: ['GET'] )]
    public function index() : JsonResponse
    {
        return new JsonResponse( $this->holidayService->getHolidaysForApi() );
    }
}