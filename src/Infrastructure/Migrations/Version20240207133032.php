<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207133032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment_old DROP FOREIGN KEY FK_83E15D119EB6921');
        $this->addSql('ALTER TABLE payment_old DROP FOREIGN KEY FK_83E15D1E5B533F9');
        $this->addSql('DROP TABLE payment_old');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_old (id INT AUTO_INCREMENT NOT NULL, appointment_id INT NOT NULL, client_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, session_id VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8_unicode_ci`, status VARCHAR(50) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8_unicode_ci`, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, INDEX IDX_83E15D119EB6921 (client_id), INDEX IDX_83E15D1E5B533F9 (appointment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE payment_old ADD CONSTRAINT FK_83E15D119EB6921 FOREIGN KEY (client_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE payment_old ADD CONSTRAINT FK_83E15D1E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
