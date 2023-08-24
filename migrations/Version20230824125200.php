<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230824125200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prestation (id INT AUTO_INCREMENT NOT NULL, category_service_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price NUMERIC(10, 2) DEFAULT NULL, duration TIME DEFAULT NULL, start_time TIME DEFAULT NULL, end_time TIME DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, avalaible_space_per_service INT DEFAULT NULL, buffer_time TIME DEFAULT NULL, INDEX IDX_51C88FADCB42F998 (category_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestation_tag (prestation_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_4C50F389E45C554 (prestation_id), INDEX IDX_4C50F38BAD26311 (tag_id), PRIMARY KEY(prestation_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FADCB42F998 FOREIGN KEY (category_service_id) REFERENCES category_service (id)');
        $this->addSql('ALTER TABLE prestation_tag ADD CONSTRAINT FK_4C50F389E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prestation_tag ADD CONSTRAINT FK_4C50F38BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_tag DROP FOREIGN KEY FK_21D9C4F4BAD26311');
        $this->addSql('ALTER TABLE service_tag DROP FOREIGN KEY FK_21D9C4F4ED5CA9E6');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2CB42F998');
        $this->addSql('DROP TABLE service_tag');
        $this->addSql('DROP TABLE service');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE service_tag (service_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_21D9C4F4BAD26311 (tag_id), INDEX IDX_21D9C4F4ED5CA9E6 (service_id), PRIMARY KEY(service_id, tag_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, category_service_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8_unicode_ci`, price NUMERIC(10, 2) DEFAULT NULL, duration TIME DEFAULT NULL, start_time TIME DEFAULT NULL, end_time TIME DEFAULT NULL, status VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8_unicode_ci`, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, avalaible_space_per_service INT DEFAULT NULL, buffer_time TIME DEFAULT NULL, INDEX IDX_E19D9AD2CB42F998 (category_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE service_tag ADD CONSTRAINT FK_21D9C4F4BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_tag ADD CONSTRAINT FK_21D9C4F4ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2CB42F998 FOREIGN KEY (category_service_id) REFERENCES category_service (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FADCB42F998');
        $this->addSql('ALTER TABLE prestation_tag DROP FOREIGN KEY FK_4C50F389E45C554');
        $this->addSql('ALTER TABLE prestation_tag DROP FOREIGN KEY FK_4C50F38BAD26311');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('DROP TABLE prestation_tag');
    }
}
