<?php

namespace App\Controller\Api;

use App\Service\BookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/api/booking', name: 'api_booking_' )]
class ApiBookingController extends AbstractController
{
    public function __construct( private readonly BookingService $bookingService )
    {
    }

    #[Route( '/', name: 'bookings', methods: ['GET'] )]
    public function getBookings()
    {
        $bookings = $this->bookingService->getBookings();
        $data = [];
        foreach ( $bookings as $booking ) {
            $data[] = array(
                'id' => $booking->getId(),
                'date' => $booking->getBookingDate()->format( 'Y-m-d' ),
                'time' => $booking->getBookingTime()->format( 'H:i' ),
                'status' => $booking->getStatus(),
                'client' => array(
                    'fullname' => $booking->getClient()->getFullname(),
                    'email' => $booking->getClient()->getEmail(),
                    'phone' => $booking->getClient()->getPhone(),
                ),
                'presentation' => array(
                    'name' => $booking->getPrestation()->getName(),
                    'description' => $booking->getPrestation()->getDescription(),
                    'duration' => $booking->getPrestation()->getDuration()->format( 'H:i' ),
                    'price' => $booking->getPrestation()->getPrice(),
                )

            );
        }

        // return json response
        return $this->json( $data );
    }
}