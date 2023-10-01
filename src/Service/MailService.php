<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailService
{
    public function __construct( private readonly MailerInterface $mailer, private readonly Environment $twig )
    {
    }

    public function createEmail( string $template, array $data ) : Email
    {

        $this->twig->addGlobal( 'format', 'html' );
        $html = $this->twig->render( $template, array_merge( $data, ['layout' => 'mails/base.html.twig'] ) );
        $this->twig->addGlobal( 'format', 'txt' );
        $text = $this->twig->render( $template, array_merge( $data, ['layout' => 'mails/base.text.twig'] ) );

        return ( new Email() )
            ->from( 'service@lazarefortune.com' )
            ->html( $html )
            ->text( $text );
    }

    public function send( Email $email ) : void
    {
        try {
            $this->mailer->send( $email );
        } catch ( TransportExceptionInterface $e ) {
            $e->getMessage();
        }
    }
}