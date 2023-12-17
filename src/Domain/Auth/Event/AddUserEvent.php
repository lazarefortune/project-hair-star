<?php

namespace App\Domain\Auth\Event;

use App\Domain\Auth\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class AddUserEvent extends Event
{
    public const NAME = 'new.user';

    public function __construct( protected User $user )
    {
    }

    public function getUser() : User
    {
        return $this->user;
    }

}