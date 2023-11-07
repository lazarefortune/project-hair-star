<?php

namespace App\Dto\Admin\Booking;

use App\Entity\Booking;
use App\Entity\Prestation;
use Symfony\Component\Validator\Constraints as Assert;

class BookingManageUpdateDto
{
    #[Assert\NotBlank( message: 'Veuillez renseigner la date de la réservation' )]
    public ?\DateTimeInterface $bookingDate = null;

    #[Assert\NotBlank( message: 'Veuillez renseigner l\'heure de la réservation' )]
    public ?\DateTimeInterface $bookingTime = null;

    public Booking $booking;

    public function __construct( Booking $booking )
    {
        $this->booking = $booking;
        $this->bookingDate = $booking->getBookingDate();
        $this->bookingTime = $booking->getBookingTime();
    }


}