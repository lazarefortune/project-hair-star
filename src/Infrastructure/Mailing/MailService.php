<?php

namespace App\Infrastructure\Mailing;

use App\Domain\Auth\Entity\User;
use App\Entity\EmailLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailService
{
    private string $senderEmail;

    public function __construct(
        private readonly MailerInterface        $mailer,
        private readonly Environment            $twig,
        private readonly EntityManagerInterface $em,
        string                                  $senderEmail,
    )
    {
        $this->senderEmail = $senderEmail;
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
            ->from( $this->senderEmail )
            ->html( $html )
            ->text( $text );
    }

    /**
     * Send email
     * @param Email $email
     * @param array $type
     * @param User|null $recipient
     * @return void
     */
    public function send( Email $email, array $type = [], User $recipient = null ) : void
    {
        try {
            $this->mailer->send( $email );

            if ( $type !== [] && $recipient !== null ) {
                $this->log( $email, $type, $recipient );
            }
        } catch ( TransportExceptionInterface $e ) {
            $e->getMessage();
        }
    }

    private function log( Email $email, array $type, User $recipient ) : void
    {
        $emailLog = new EmailLog();
        $emailLog->setType( $type['value'] );
        $emailLog->setTypeDescription( $type['description'] );
        $emailLog->setRecipient( $recipient );
        $emailLog->setSentAt( new \DateTime() );
        $emailLog->setContentHtml( $email->getHtmlBody() );
        $emailLog->setContentText( $email->getTextBody() );

        $this->em->persist( $emailLog );
        $this->em->flush();
    }
}