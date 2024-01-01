<?php

namespace App\Infrastructure\Mailing;

use App\Domain\Auth\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailService
{
    private string $senderEmail;
    private string $senderName;

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment     $twig,
        string                           $senderEmail,
        string                           $senderName
    )
    {
        $this->senderEmail = $senderEmail;
        $this->senderName = $senderName;
    }

    /**
     * @param string $template
     * @param array<string, mixed> $data
     * @return Email
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createEmail( string $template, array $data ) : Email
    {

        $this->twig->addGlobal( 'format', 'html' );
        $html = $this->twig->render( $template, array_merge( $data, ['layout' => 'mails/base.html.twig'] ) );
        $this->twig->addGlobal( 'format', 'txt' );
        $text = $this->twig->render( $template, array_merge( $data, ['layout' => 'mails/base.text.twig'] ) );

        return ( new Email() )
            ->from( new Address( $this->senderEmail, $this->senderName ) )
            ->html( $html )
            ->text( $text );
    }

    /**
     * Send email
     * @param Email $email
     * @return void
     */
    public function send( Email $email ) : void
    {
        try {
            $this->mailer->send( $email );
        } catch ( TransportExceptionInterface $e ) {
            $e->getMessage();
        }
    }
}