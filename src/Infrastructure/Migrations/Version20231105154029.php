<?php

declare( strict_types=1 );

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231105154029 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up( Schema $schema ) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql( 'CREATE TABLE client_agree_term (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB' );
        $this->addSql( 'ALTER TABLE booking ADD amount INT DEFAULT NULL' );
    }

    public function down( Schema $schema ) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql( 'DROP TABLE client_agree_term' );
        $this->addSql( 'ALTER TABLE booking DROP amount' );
    }
}
