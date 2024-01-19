<?php

namespace App\Domain\Payment\Event;

class PaymentSuccessEvent
{
    public function __construct(
        readonly private int $appointmentId,
    )
    {
    }

    public function getAppointmentId() : int
    {
        return $this->appointmentId;
    }
}