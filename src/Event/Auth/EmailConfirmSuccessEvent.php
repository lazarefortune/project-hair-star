<?php

namespace App\Event\Auth;

use App\Entity\User;

class EmailConfirmSuccessEvent
{
    const NAME = 'email.confirm.success';

    public function __construct( protected User $user )
    {
    }

    public function getUser() : User
    {
        return $this->user;
    }
}