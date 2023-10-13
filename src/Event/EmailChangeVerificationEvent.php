<?php

namespace App\Event;

use App\Entity\EmailVerification;

class EmailChangeVerificationEvent
{

    public function __construct( public EmailVerification $emailVerification )
    {
    }
}