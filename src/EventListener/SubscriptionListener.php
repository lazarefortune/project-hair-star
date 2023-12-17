<?php

namespace App\EventListener;

use App\Domain\Auth\Event\AddUserEvent;

class SubscriptionListener
{
    public function onNewUser( AddUserEvent $event ) : void
    {
        $user = $event->getUser();

        // envoie de l'email de confirmation
    }

}