<?php

namespace App\Dto\Admin\Booking;

use App\Entity\Booking;
use App\Entity\Prestation;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class BookingDto
{
    #[Assert\NotBlank( message: 'Veuillez renseigner la date de la réservation' )]
    public ?\DateTimeInterface $bookingDate = null;

    #[Assert\NotBlank( message: 'Veuillez renseigner l\'heure de la réservation' )]
    public ?\DateTimeInterface $bookingTime = null;

    #[Assert\NotBlank( message: 'Veuillez renseigner le client' )]
    public User $client;

    #[Assert\NotBlank( message: 'Veuillez renseigner la prestation' )]
    public Prestation $prestation;

    public Booking $booking;

    public function __construct( Booking $booking )
    {
        $this->booking = $booking;
        if ( $booking->getId() ) {
            $this->client = $booking->getClient();
            $this->prestation = $booking->getPrestation();
        }
        $this->bookingDate = $booking->getBookingDate();
        $this->bookingTime = $booking->getBookingTime();
    }
}