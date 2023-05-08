<?php

namespace App\Command;

use App\Data\PermissionData;
use App\Data\RoleData;
use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\PermissionRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
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

    private PermissionRepository $permissionRepository;
    private RoleRepository $roleRepository;
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct( PermissionRepository $permissionRepository, RoleRepository $roleRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em )
    {
        $this->permissionRepository = $permissionRepository;
        $this->roleRepository = $roleRepository;
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

        // Création des permissions

        if ( $this->permissionRepository->findAll() ) {
            $this->truncatePermissions( $input, $output );
        } else {
            $this->createPermissions( $input, $output );
        }

        // Création des rôles
        // If is not empty, we don't create all roles
        if ( $this->roleRepository->findAll() ) {
            $this->truncateRoles( $input, $output );
        } else {
            $this->createRoles( $input, $output );
        }

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

    private function createPermissions( InputInterface $input, OutputInterface $output ) : void
    {
        $permissions = PermissionData::getDefaultPermissions();

        foreach ( $permissions as $permission ) {
            $permissionAdmin = new Permission();
            $permissionAdmin->setName( $permission['name'] );
            $permissionAdmin->setDisplayName( $permission['displayName'] );
            $permissionAdmin->setDescription( $permission['description'] );
            $this->em->persist( $permissionAdmin );
        }

        $this->em->flush();

        $io = new SymfonyStyle( $input, $output );
        $io->success( 'All permissions have been created' );
    }

    private function truncatePermissions( InputInterface $input, OutputInterface $output ) : void
    {
        // ask to user if he want to truncate table permissions
        $io = new SymfonyStyle( $input, $output );
        $io->warning( 'Permissions already created' );
        $io->writeln( 'Do you want to truncate permissions table ?' );
        $io->writeln( 'All permissions will be deleted' );
        $io->writeln( 'All roles will be deleted' );
        $io->writeln( 'This action is irreversible' );
        $answer = $io->ask( 'Y(es) or N(o)' );
        if ( in_array( $answer, ['Y', 'y', 'yes', 'Yes'] ) ) {
            // remove all users
            $this->removeAllUsers();
            $io->success( 'Users table truncated' );


            // Delete all Roles and Permissions
            $roles = $this->roleRepository->findAll();
            foreach ( $roles as $role ) {
                $this->em->remove( $role );
            }

            $io->success( 'Roles table truncated' );

            $permissions = $this->permissionRepository->findAll();
            foreach ( $permissions as $permission ) {
                $this->em->remove( $permission );
            }
            $this->em->flush();

            $io->success( 'Permissions table truncated' );

            // Create permissions
            $this->createPermissions( $input, $output );
        } else {
            $io->warning( 'Permissions table not truncated' );
        }
    }

    private function createRoles( InputInterface $input, OutputInterface $output ) : void
    {
        $io = new SymfonyStyle( $input, $output );

        $roles = RoleData::getDefaultRoles();

        $io->writeln( 'Create roles' );
        foreach ( $roles as $role ) {
            $roleAdmin = new Role();
            $roleAdmin->setName( $role['name'] );
            $roleAdmin->setDisplayName( $role['displayName'] );
            $roleAdmin->setDescription( $role['description'] );

            // if is admin role, add all permissions
            if ( $role['name'] === 'ROLE_ADMIN' ) {
                $permissions = $this->em->getRepository( Permission::class )->findAll();
                foreach ( $permissions as $permission ) {
                    $roleAdmin->addPermission( $permission );
                }
            }

            $this->em->persist( $roleAdmin );
        }

        $this->em->flush();

        $io->success( 'Roles created successfully' );
    }

    private function truncateRoles( InputInterface $input, OutputInterface $output ) : void
    {
        $io = new SymfonyStyle( $input, $output );
        $io->warning( 'Roles already created' );
        $io->writeln( 'Do you want to truncate roles table ?' );
        $io->writeln( 'All roles will be deleted' );
        $io->writeln( 'This action is irreversible' );
        $answer = $io->ask( 'Y(es) or N(o)' );
        if ( in_array( $answer, ['Y', 'y', 'yes', 'Yes'] ) ) {
            // remove all users
            $this->removeAllUsers();
            $io->success( 'Users table truncated' );

            // Delete all Roles
            $roles = $this->roleRepository->findAll();
            foreach ( $roles as $role ) {
                $this->em->remove( $role );
            }
            $this->em->flush();
            $io->success( 'Roles table truncated' );

            // Create roles
            $this->createRoles( $input, $output );
        } else {
            $io->warning( 'Roles table not truncated' );
        }
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
                $newUser->setRole( $this->roleRepository->findOneBy( ['name' => 'ROLE_ADMIN'] ) );
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