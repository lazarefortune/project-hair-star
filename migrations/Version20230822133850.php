<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822133850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service ADD category_service_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2CB42F998 FOREIGN KEY (category_service_id) REFERENCES category_service (id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD2CB42F998 ON service (category_service_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2CB42F998');
        $this->addSql('DROP INDEX IDX_E19D9AD2CB42F998 ON service');
        $this->addSql('ALTER TABLE service DROP category_service_id');
    }
}
