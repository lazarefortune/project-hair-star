<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    public function __construct( private readonly MailerInterface $mailer )
    {
    }

    public function send( string $to, string $subject, string $body, string $template ) : void
    {
        try {
            $this->mailer->send( ( new TemplatedEmail() )
                ->from( 'service@lazarefortune.com' )
                ->to( $to )
                ->subject( $subject )
                ->htmlTemplate( $template )
                ->context( [
                    'body' => $body,
                ] )
            );
        } catch ( TransportExceptionInterface $e ) {
            $e->getMessage();
        }

    }
}