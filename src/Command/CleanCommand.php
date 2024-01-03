<?php

namespace App\Command;

use App\Domain\Auth\Entity\EmailVerification;
use App\Domain\Tag\Entity\Tag;
use App\Infrastructure\Orm\CleanableRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Nettoyage de la base de données (cron quotidien)
 */
#[AsCommand(
    name: 'app:clean',
    description: 'Command de nettoyage',
)]
class CleanCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    protected function execute( InputInterface $input, OutputInterface $output ) : int
    {

        $io = new SymfonyStyle( $input, $output );
        $this->clean( $io, EmailVerification::class, 'email verification requests' );
        $this->clean( $io, Tag::class, 'unused tags' );


        return Command::SUCCESS;
    }

    /**
     * @throws Exception
     */
    private function clean( SymfonyStyle $io, string $entity, string $noun ) : void
    {
        $repository = $this->em->getRepository( $entity );
        if ( !$repository instanceof CleanableRepositoryInterface ) {
            throw new Exception( sprintf( 'Le repository %s n\'implémente pas l\'interface CleanableRepositoryInterface', $entity ) );
        }
        $count = $repository->clean();
        $io->success( sprintf( 'Delete %d %s', $count, $noun ) );
    }
}