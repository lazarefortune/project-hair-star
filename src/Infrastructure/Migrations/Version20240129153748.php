<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240129153748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_old (id INT AUTO_INCREMENT NOT NULL, appointment_id INT NOT NULL, client_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, session_id VARCHAR(255) DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, INDEX IDX_83E15D1E5B533F9 (appointment_id), INDEX IDX_83E15D119EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, total_amount DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_723705D119EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_old ADD CONSTRAINT FK_83E15D1E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id)');
        $this->addSql('ALTER TABLE payment_old ADD CONSTRAINT FK_83E15D119EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D119EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DE5B533F9');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D19EB6921');
        $this->addSql('DROP INDEX IDX_6D28840D19EB6921 ON payment');
        $this->addSql('DROP INDEX IDX_6D28840DE5B533F9 ON payment');
        $this->addSql('ALTER TABLE payment ADD transaction_id INT NOT NULL, ADD payment_method VARCHAR(255) NOT NULL, DROP appointment_id, DROP client_id, DROP session_id, CHANGE status status VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D2FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
        $this->addSql('CREATE INDEX IDX_6D28840D2FC0CB0F ON payment (transaction_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D2FC0CB0F');
        $this->addSql('ALTER TABLE payment_old DROP FOREIGN KEY FK_83E15D1E5B533F9');
        $this->addSql('ALTER TABLE payment_old DROP FOREIGN KEY FK_83E15D119EB6921');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D119EB6921');
        $this->addSql('DROP TABLE payment_old');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP INDEX IDX_6D28840D2FC0CB0F ON payment');
        $this->addSql('ALTER TABLE payment ADD client_id INT NOT NULL, ADD session_id VARCHAR(255) DEFAULT NULL, DROP payment_method, CHANGE status status VARCHAR(50) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE transaction_id appointment_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D19EB6921 FOREIGN KEY (client_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6D28840D19EB6921 ON payment (client_id)');
        $this->addSql('CREATE INDEX IDX_6D28840DE5B533F9 ON payment (appointment_id)');
    }
}
