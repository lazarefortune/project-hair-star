<?php

namespace App\Event;

use App\Entity\EmailVerification;

class EmailVerificationEvent
{

    public function __construct( public EmailVerification $emailVerification )
    {
    }
}