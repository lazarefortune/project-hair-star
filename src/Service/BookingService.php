<?php

namespace App\Service;

use App\Entity\Booking;
use App\Repository\BookingRepository;

class BookingService
{
    public function __construct( private readonly BookingRepository $bookingRepository )
    {
    }

    public function getBookings() : array
    {
        return $this->bookingRepository->findAll();
    }

    public function addBooking( Booking $booking ) : void
    {
        $this->bookingRepository->save( $booking, true );
    }

    public function deleteBooking( Booking $booking ) : void
    {
        $this->bookingRepository->remove( $booking, true );
    }
}