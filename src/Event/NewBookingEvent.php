<?php

namespace App\Event;

use App\Entity\Booking;

class NewBookingEvent
{
    public function __construct( private readonly Booking $booking )
    {
    }

    public function getBooking() : Booking
    {
        return $this->booking;
    }

}