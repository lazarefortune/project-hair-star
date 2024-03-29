<?php

namespace App\Domain\Client\Subscriber;

use App\Domain\Client\Event\DeleteClientEvent;
use App\Infrastructure\Mailing\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ClientSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MailService           $mailService,
        private readonly UrlGeneratorInterface $urlGenerator
    )
    {
    }

    public static function getSubscribedEvents() : array
    {
        return [
            DeleteClientEvent::NAME => 'onDeleteClient',
        ];
    }

    public function onDeleteClient( DeleteClientEvent $event ) : void
    {
        $client = $event->getUser();

        $email = $this->mailService->createEmail( 'mails/client/account-remove.twig', [
            'client' => $client,
            'contact_url' => $this->urlGenerator->generate( 'app_contact', [], UrlGeneratorInterface::ABSOLUTE_URL )
        ] )
            ->to( $client->getEmail() )
            ->subject( 'Votre compte a été supprimé' );

        $this->mailService->send( $email );
    }
}