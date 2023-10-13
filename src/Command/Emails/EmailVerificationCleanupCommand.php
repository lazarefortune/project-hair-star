<?php

namespace App\Command\Emails;

use App\Repository\EmailVerificationRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EmailVerificationCleanupCommand extends Command
{
    protected static $defaultName = 'app:email-verification:cleanup';

    public function __construct( private readonly EmailVerificationRepository $emailVerificationRepository )
    {
        parent::__construct();
    }

    protected function configure() : void
    {
        $this
            ->setDescription( 'Deletes expired and already verified email verification requests.' )
            ->setHelp( 'This command allows you to delete expired and already verified email verification requests.' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ) : int
    {
        $io = new SymfonyStyle( $input, $output );
        $io->title( 'Email verification cleanup' );
        $io->text( 'This command allows you to delete expired and already verified email verification requests.' );

        $count = $this->emailVerificationRepository->deleteExpiredEmailVerifications();

        $io->success( sprintf( 'Successfully deleted %d email verification requests.', $count ) );

        return Command::SUCCESS;
    }

}