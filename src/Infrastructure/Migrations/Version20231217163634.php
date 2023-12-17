<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231217163634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE19EB6921');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE9E45C554');
        $this->addSql('DROP TABLE booking');
        $this->addSql('ALTER TABLE appointment CHANGE booking_date date DATE NOT NULL, CHANGE booking_time time TIME NOT NULL');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D3301C60');
        $this->addSql('DROP INDEX IDX_6D28840D3301C60 ON payment');
        $this->addSql('ALTER TABLE payment CHANGE booking_id appointment_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id)');
        $this->addSql('CREATE INDEX IDX_6D28840DE5B533F9 ON payment (appointment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, prestation_id INT NOT NULL, booking_date DATE NOT NULL, booking_time TIME NOT NULL, status VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8_unicode_ci`, amount NUMERIC(10, 2) DEFAULT NULL, payment_status VARCHAR(50) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8_unicode_ci`, token VARCHAR(50) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8_unicode_ci`, INDEX IDX_E00CEDDE19EB6921 (client_id), INDEX IDX_E00CEDDE9E45C554 (prestation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE19EB6921 FOREIGN KEY (client_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE9E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE appointment CHANGE date booking_date DATE NOT NULL, CHANGE time booking_time TIME NOT NULL');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DE5B533F9');
        $this->addSql('DROP INDEX IDX_6D28840DE5B533F9 ON payment');
        $this->addSql('ALTER TABLE payment CHANGE appointment_id booking_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D3301C60 FOREIGN KEY (booking_id) REFERENCES appointment (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6D28840D3301C60 ON payment (booking_id)');
    }
}
