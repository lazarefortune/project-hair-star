<?php

namespace App\Domain\Auth\Event;

use App\Domain\Auth\Entity\EmailVerification;

class EmailChangeVerificationEvent
{

    public function __construct( public EmailVerification $emailVerification )
    {
    }
}