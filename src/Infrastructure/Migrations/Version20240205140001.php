<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240205140001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment CHANGE amount amount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE prestation CHANGE price price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE realisation CHANGE tarif tarif DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment CHANGE amount amount NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE prestation CHANGE price price NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE realisation CHANGE tarif tarif NUMERIC(10, 2) DEFAULT NULL');
    }
}
