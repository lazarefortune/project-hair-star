<?php

namespace App\Event\Booking;

use App\Entity\Booking;

class CanceledBookingEvent
{
    public function __construct( private readonly Booking $booking )
    {
    }

    public function getBooking() : Booking
    {
        return $this->booking;
    }
}