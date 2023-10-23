<?php

namespace App\Service;

use App\Dto\Admin\Booking\BookingDto;
use App\Entity\Booking;
use App\Event\Booking\CanceledBookingEvent;
use App\Event\Booking\ConfirmedBookingEvent;
use App\Event\Booking\NewBookingEvent;
use App\Event\Booking\UpdateBookingEvent;
use App\Repository\BookingRepository;
use Psr\EventDispatcher\EventDispatcherInterface;

class BookingService
{
    public function __construct( private readonly BookingRepository $bookingRepository, private EventDispatcherInterface $eventDispatcher )
    {
    }

    /**
     * @return Booking[]
     */
    public function getBookings() : array
    {
        return $this->bookingRepository->findAllOrderedByDate();
    }

    public function addBooking( BookingDto $bookingDto ) : void
    {
        $booking = ( new Booking() )->setClient( $bookingDto->client )
            ->setPrestation( $bookingDto->prestation )
            ->setBookingDate( $bookingDto->bookingDate )
            ->setBookingTime( $bookingDto->bookingTime );

        $this->bookingRepository->save( $booking, true );

        $newBookingEvent = new NewBookingEvent( $booking );

        $this->eventDispatcher->dispatch( $newBookingEvent );
    }

    public function updateBookingWithDto( BookingDto $bookingDto ) : void
    {
        $booking = $bookingDto->booking->setClient( $bookingDto->client )
            ->setPrestation( $bookingDto->prestation )
            ->setBookingDate( $bookingDto->bookingDate )
            ->setBookingTime( $bookingDto->bookingTime );

        $this->bookingRepository->save( $booking, true );

        $this->eventDispatcher->dispatch( new UpdateBookingEvent( $booking ) );
    }

    public function deleteBooking( Booking $booking ) : void
    {
        $this->bookingRepository->remove( $booking, true );
    }

    public function updateBooking( Booking $booking ) : void
    {
        $this->bookingRepository->save( $booking, true );
    }

    public function confirmBooking( Booking $booking ) : void
    {
        $booking->setIsConfirmed( true );
        $this->bookingRepository->save( $booking, true );

        $this->eventDispatcher->dispatch( new ConfirmedBookingEvent( $booking ) );
    }

    public function cancelBooking( Booking $booking ) : void
    {
        $booking->setIsConfirmed( false );
        $this->bookingRepository->save( $booking, true );

        $this->eventDispatcher->dispatch( new CanceledBookingEvent( $booking ) );
    }

    /**
     * @return Booking[]
     */
    public function getReservedBookings() : array
    {
        return $this->bookingRepository->findReservedBookings();
    }
}