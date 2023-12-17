<?php

namespace App\Domain\Appointment\Dto;

use App\Domain\Appointment\Entity\Appointment;
use App\Domain\Auth\Entity\User;
use App\Domain\Prestation\Entity\Prestation;
use Symfony\Component\Validator\Constraints as Assert;

class AppointmentData
{
    #[Assert\NotBlank( message: 'Veuillez renseigner la date du rendez-vous' )]
    public ?\DateTimeInterface $date = null;

    #[Assert\NotBlank( message: 'Veuillez renseigner l\'heure du rendez-vous' )]
    public ?\DateTimeInterface $time = null;

    #[Assert\NotBlank( message: 'Veuillez renseigner le client' )]
    public User $client;

    #[Assert\NotBlank( message: 'Veuillez renseigner la prestation' )]
    public Prestation $prestation;

    public Appointment $appointment;

    public function __construct( Appointment $appointment )
    {
        $this->appointment = $appointment;
        if ( $appointment->getId() ) {
            $this->client = $appointment->getClient();
            $this->prestation = $appointment->getPrestation();
        }
        $this->date = $appointment->getDate();
        $this->time = $appointment->getTime();
    }
}