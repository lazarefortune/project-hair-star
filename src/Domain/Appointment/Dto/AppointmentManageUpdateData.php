<?php

namespace App\Domain\Appointment\Dto;

use App\Domain\Appointment\Entity\Appointment;
use Symfony\Component\Validator\Constraints as Assert;

class AppointmentManageUpdateData
{
    #[Assert\NotBlank( message: 'Veuillez renseigner la date de la réservation' )]
    public ?\DateTimeInterface $date = null;

    #[Assert\NotBlank( message: 'Veuillez renseigner l\'heure de la réservation' )]
    public ?\DateTimeInterface $time = null;

    public Appointment $appointment;

    public function __construct( Appointment $appointment )
    {
        $this->appointment = $appointment;
        $this->date = $appointment->getDate();
        $this->time = $appointment->getTime();
    }


}