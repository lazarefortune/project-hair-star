<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304205407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_available_to_client TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appointment ADD transaction_id INT DEFAULT NULL, ADD comment LONGTEXT DEFAULT NULL, ADD nb_adults INT NOT NULL, ADD nb_children INT NOT NULL, ADD sub_total INT DEFAULT NULL, ADD total INT DEFAULT NULL, ADD access_token VARCHAR(50) DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL, DROP payment_status, DROP token, CHANGE amount applied_discount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8442FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
        $this->addSql('CREATE INDEX IDX_FE38F8442FC0CB0F ON appointment (transaction_id)');
        $this->addSql('ALTER TABLE transaction DROP total_amount, DROP transaction_item_type, CHANGE transaction_item_id amount INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8442FC0CB0F');
        $this->addSql('DROP INDEX IDX_FE38F8442FC0CB0F ON appointment');
        $this->addSql('ALTER TABLE appointment ADD token VARCHAR(50) DEFAULT NULL, DROP transaction_id, DROP comment, DROP nb_adults, DROP nb_children, DROP sub_total, DROP total, DROP created_at, DROP updated_at, CHANGE applied_discount amount DOUBLE PRECISION DEFAULT NULL, CHANGE access_token payment_status VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD total_amount DOUBLE PRECISION NOT NULL, ADD transaction_item_type VARCHAR(255) NOT NULL, CHANGE amount transaction_item_id INT NOT NULL');
    }
}
