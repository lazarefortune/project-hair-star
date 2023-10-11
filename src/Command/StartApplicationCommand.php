<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

/**
 * @AsCommand(
 *     name="app:create-first-user",
 *     description="Create the first user"
 * )
 */
class StartApplicationCommand extends Command
{
    protected static $defaultName = 'app:start';
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct( UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em )
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->em = $em;
        parent::__construct();
    }

    protected function configure() : void
    {
        $this
            ->setDescription( 'Create all roles and permissions' )
            ->setHelp( 'This command generates all roles and permissions' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ) : int
    {
        $io = new SymfonyStyle( $input, $output );

        // ask to create first user
        $this->createFistUser( $input, $output );

        return Command::SUCCESS;
    }

    private function removeAllUsers() : void
    {
        $users = $this->em->getRepository( User::class )->findAll();
        foreach ( $users as $user ) {
            $this->em->remove( $user );
        }
        $this->em->flush();
    }

    private function createFistUser( InputInterface $input, OutputInterface $output ) : void
    {
        // Ask to create first user
        $io = new SymfonyStyle( $input, $output );
        $io->writeln( 'Create first user' );
        $io->writeln( 'You need to create first user' );
        $io->writeln( 'This user will be administrator' );
        $io->writeln( 'This user will have all permissions' );
        $io->writeln( 'All users will be deleted' );
        $io->writeln( 'Do you want to create first user ?' );
        $answer = $io->ask( 'Y(es) or N(o)' );
        // show loading bar

        if ( in_array( $answer, ['Y', 'y', 'yes', 'Yes'] ) ) {
            $this->removeAllUsers();
            $io->success( 'Users table truncated' );
            $io->writeln( 'Create first user' );
            try {
                $newUser = new User();
                $newUser->setEmail( 'admin@gmail.com' );
                $newUser->setPassword( $this->userPasswordHasher->hashPassword( $newUser, 'admin' ) );
                $newUser->setFullName( 'admin' );
                $newUser->setPhone( '0122334455' );
                $newUser->setIsVerified( true );
                $newUser->setRoles( ['ROLE_ADMIN'] );
                $this->em->persist( $newUser );
                $this->em->flush();

                $io->success( 'User created successfully' );
                $io->writeln( 'Email: admin@gmail.com' );
                $io->writeln( 'Password: admin' );

            } catch ( \Exception $e ) {
                $io->error( $e->getMessage() );
            }
        } else {
            $io->warning( 'First user not created' );
        }

    }

}