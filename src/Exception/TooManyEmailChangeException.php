<?php

namespace App\Exception;

use App\Entity\EmailVerification;

class TooManyEmailChangeException extends \Exception
{
    public function __construct( public EmailVerification $emailVerification )
    {
        parent::__construct();
    }
}