<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230824130657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FADCB42F998');
        $this->addSql('CREATE TABLE category_prestation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE category_service');
        $this->addSql('DROP INDEX IDX_51C88FADCB42F998 ON prestation');
        $this->addSql('ALTER TABLE prestation ADD category_prestation_id INT DEFAULT NULL, ADD avalaible_space_per_prestation INT DEFAULT NULL, DROP category_service_id, DROP avalaible_space_per_service');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD809EE01F FOREIGN KEY (category_prestation_id) REFERENCES category_prestation (id)');
        $this->addSql('CREATE INDEX IDX_51C88FAD809EE01F ON prestation (category_prestation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FAD809EE01F');
        $this->addSql('CREATE TABLE category_service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8_unicode_ci`, is_active TINYINT(1) NOT NULL, description LONGTEXT CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE category_prestation');
        $this->addSql('DROP INDEX IDX_51C88FAD809EE01F ON prestation');
        $this->addSql('ALTER TABLE prestation ADD category_service_id INT DEFAULT NULL, ADD avalaible_space_per_service INT DEFAULT NULL, DROP category_prestation_id, DROP avalaible_space_per_prestation');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FADCB42F998 FOREIGN KEY (category_service_id) REFERENCES category_service (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_51C88FADCB42F998 ON prestation (category_service_id)');
    }
}
