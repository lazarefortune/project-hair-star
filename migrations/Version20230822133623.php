<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822133623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service_category_service DROP FOREIGN KEY FK_8100FDBDCB42F998');
        $this->addSql('ALTER TABLE service_category_service DROP FOREIGN KEY FK_8100FDBDED5CA9E6');
        $this->addSql('DROP TABLE service_category_service');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE service_category_service (service_id INT NOT NULL, category_service_id INT NOT NULL, INDEX IDX_8100FDBDCB42F998 (category_service_id), INDEX IDX_8100FDBDED5CA9E6 (service_id), PRIMARY KEY(service_id, category_service_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE service_category_service ADD CONSTRAINT FK_8100FDBDCB42F998 FOREIGN KEY (category_service_id) REFERENCES category_service (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_category_service ADD CONSTRAINT FK_8100FDBDED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
