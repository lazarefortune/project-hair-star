<?php

namespace App\Service;

use App\Entity\Booking;
use App\Event\NewBookingEvent;
use App\Repository\BookingRepository;
use Psr\EventDispatcher\EventDispatcherInterface;

class BookingService
{
    public function __construct( private readonly BookingRepository $bookingRepository, private EventDispatcherInterface $eventDispatcher )
    {
    }

    public function getBookings() : array
    {
        return $this->bookingRepository->findAllOrderedByDate();
    }

    public function addBooking( Booking $booking ) : void
    {
        $this->bookingRepository->save( $booking, true );

        $newBookingEvent = new NewBookingEvent( $booking );

        $this->eventDispatcher->dispatch( $newBookingEvent );
    }

    public function deleteBooking( Booking $booking ) : void
    {
        $this->bookingRepository->remove( $booking, true );
    }
}