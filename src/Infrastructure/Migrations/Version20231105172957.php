<?php

declare( strict_types=1 );

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231105172957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up( Schema $schema ) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql( 'CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, booking_id INT NOT NULL, client_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, session_id VARCHAR(255) DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, INDEX IDX_6D28840D3301C60 (booking_id), INDEX IDX_6D28840D19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB' );
        $this->addSql( 'ALTER TABLE payment ADD CONSTRAINT FK_6D28840D3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)' );
        $this->addSql( 'ALTER TABLE payment ADD CONSTRAINT FK_6D28840D19EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)' );
    }

    public function down( Schema $schema ) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql( 'ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D3301C60' );
        $this->addSql( 'ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D19EB6921' );
        $this->addSql( 'DROP TABLE payment' );
    }
}
