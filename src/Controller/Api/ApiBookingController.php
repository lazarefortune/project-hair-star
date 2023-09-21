<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/api/booking', name: 'api_booking_' )]
class ApiBookingController extends AbstractController
{
    public function getBookings()
    {
        // return json response
        return $this->json( [
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/ApiBookingController.php',
        ] );
    }
}