<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230824121911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE service_tag (service_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_21D9C4F4ED5CA9E6 (service_id), INDEX IDX_21D9C4F4BAD26311 (tag_id), PRIMARY KEY(service_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE service_tag ADD CONSTRAINT FK_21D9C4F4ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_tag ADD CONSTRAINT FK_21D9C4F4BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service_tag DROP FOREIGN KEY FK_21D9C4F4ED5CA9E6');
        $this->addSql('ALTER TABLE service_tag DROP FOREIGN KEY FK_21D9C4F4BAD26311');
        $this->addSql('DROP TABLE service_tag');
    }
}
