<?php

namespace App\Domain\Appointment\Dto;

use App\Domain\Appointment\Entity\Appointment;
use Symfony\Component\Validator\Constraints as Assert;

class AppointmentManageUpdateData
{
    #[Assert\NotBlank( message: 'Veuillez renseigner la date de la réservation' )]
    public ?\DateTimeInterface $appointmentDate = null;

    #[Assert\NotBlank( message: 'Veuillez renseigner l\'heure de la réservation' )]
    public ?\DateTimeInterface $appointmentTime = null;

    public Appointment $appointment;

    public function __construct( Appointment $appointment )
    {
        $this->appointment = $appointment;
        $this->appointmentDate = $appointment->getDate();
        $this->appointmentTime = $appointment->getTime();
    }


}