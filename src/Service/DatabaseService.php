<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpKernel\KernelInterface;

class DatabaseService
{

    private const COMMAND_CREATE_DATABASE = 'doctrine:database:create';
    private const COMMAND_MAKE_MIGRATION = 'make:migration';
    private const COMMAND_MIGRATE_DATABASE = 'php bin/console doctrine:migrations:migrate';
    private const COMMAND_CREATE_SCHEMA = 'doctrine:schema:create';
    private const COMMAND_UPDATE_SCHEMA = 'doctrine:schema:update';
    private const COMMAND_LOAD_FIXTURES = 'doctrine:fixtures:load';

    public function __construct( private readonly KernelInterface $kernel )
    {
    }

    /**
     * Create database
     * @return bool
     */
    public function createDatabase() : bool
    {
        $application = new Application( $this->kernel );
        $application->setAutoExit( false );

        $this->runCommand( $application, self::COMMAND_CREATE_DATABASE );
//        $this->runCommand( $application, self::COMMAND_MAKE_MIGRATION );
//        $this->runCommand( $application, self::COMMAND_MIGRATE_DATABASE );

        $this->runCommand( $application, self::COMMAND_UPDATE_SCHEMA, ['--force' => true] );

        $this->runCommand( $application, self::COMMAND_LOAD_FIXTURES, ['--append' => true] );

        return Command::SUCCESS;
    }

    /**
     * Run command
     * @param Application $application
     * @param string $command
     * @param array $options
     * @return int
     */
    private function runCommand( Application $application, string $command, array $options = [] ) : int
    {
        $input = new ArrayInput(
            array_merge( ['command' => $command], $options )
        );

        try {
            return $application->run( $input ) === Command::SUCCESS;
        } catch ( \Exception $e ) {
            return Command::FAILURE;
        }
    }
}