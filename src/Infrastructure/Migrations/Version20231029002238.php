<?php

declare( strict_types=1 );

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231029002238 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up( Schema $schema ) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql( 'CREATE TABLE email_log (id INT AUTO_INCREMENT NOT NULL, recipient_id INT NOT NULL, type VARCHAR(255) NOT NULL, sent_at DATETIME NOT NULL, content_html LONGTEXT NOT NULL, content_text LONGTEXT DEFAULT NULL, INDEX IDX_6FB4883E92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB' );
        $this->addSql( 'ALTER TABLE email_log ADD CONSTRAINT FK_6FB4883E92F8F78 FOREIGN KEY (recipient_id) REFERENCES `user` (id)' );
    }

    public function down( Schema $schema ) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql( 'ALTER TABLE email_log DROP FOREIGN KEY FK_6FB4883E92F8F78' );
        $this->addSql( 'DROP TABLE email_log' );
    }
}
