<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304224721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE realisation ADD amount INT DEFAULT NULL, DROP tarif, CHANGE is_public is_online TINYINT(1) NOT NULL, CHANGE is_tarif_public boolean TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE realisation ADD tarif DOUBLE PRECISION DEFAULT NULL, DROP amount, CHANGE is_online is_public TINYINT(1) NOT NULL, CHANGE boolean is_tarif_public TINYINT(1) DEFAULT NULL');
    }
}
