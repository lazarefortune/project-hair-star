<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DailyCronCommand extends Command
{
    protected static $defaultName = 'app:daily-cron';

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure() : void
    {
        $this->setDescription( 'Commande de nettoyage' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ) : int
    {
        $io = new SymfonyStyle( $input, $output );
        $io->success( 'Daily cron executed' );
        return Command::SUCCESS;
    }

}