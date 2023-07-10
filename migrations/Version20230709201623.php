<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230709201623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price INT DEFAULT NULL, duration TIME DEFAULT NULL, start_time TIME DEFAULT NULL, end_time TIME DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, avalaible_space_per_service INT DEFAULT NULL, buffer_time TIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_category_service (service_id INT NOT NULL, category_service_id INT NOT NULL, INDEX IDX_8100FDBDED5CA9E6 (service_id), INDEX IDX_8100FDBDCB42F998 (category_service_id), PRIMARY KEY(service_id, category_service_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE service_category_service ADD CONSTRAINT FK_8100FDBDED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_category_service ADD CONSTRAINT FK_8100FDBDCB42F998 FOREIGN KEY (category_service_id) REFERENCES category_service (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service_category_service DROP FOREIGN KEY FK_8100FDBDED5CA9E6');
        $this->addSql('ALTER TABLE service_category_service DROP FOREIGN KEY FK_8100FDBDCB42F998');
        $this->addSql('DROP TABLE category_service');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE service_category_service');
    }
}
