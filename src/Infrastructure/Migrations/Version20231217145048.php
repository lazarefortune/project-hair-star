<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231217145048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, prestation_id INT NOT NULL, booking_date DATE NOT NULL, booking_time TIME NOT NULL, status VARCHAR(50) NOT NULL, amount NUMERIC(10, 2) DEFAULT NULL, payment_status VARCHAR(50) DEFAULT NULL, token VARCHAR(50) DEFAULT NULL, INDEX IDX_E00CEDDE19EB6921 (client_id), INDEX IDX_E00CEDDE9E45C554 (prestation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_prestation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client_agree_term (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) DEFAULT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_log (id INT AUTO_INCREMENT NOT NULL, recipient_id INT NOT NULL, type VARCHAR(255) NOT NULL, sent_at DATETIME NOT NULL, content_html LONGTEXT NOT NULL, content_text LONGTEXT DEFAULT NULL, type_description LONGTEXT DEFAULT NULL, INDEX IDX_6FB4883E92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_verification (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FE22358F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE holiday (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, UNIQUE INDEX UNIQ_DC9AB2342B36786B (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_realisation (id INT AUTO_INCREMENT NOT NULL, realisation_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_25732A80B685E551 (realisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, booking_id INT NOT NULL, client_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, session_id VARCHAR(255) DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, INDEX IDX_6D28840D3301C60 (booking_id), INDEX IDX_6D28840D19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestation (id INT AUTO_INCREMENT NOT NULL, category_prestation_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price NUMERIC(10, 2) DEFAULT NULL, duration TIME DEFAULT NULL, start_time TIME DEFAULT NULL, end_time TIME DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, avalaible_space_per_prestation INT DEFAULT NULL, buffer_time TIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, consider_children_for_price TINYINT(1) DEFAULT NULL, children_age_range INT DEFAULT NULL, children_price_percentage DOUBLE PRECISION DEFAULT NULL, INDEX IDX_51C88FAD809EE01F (category_prestation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestation_tag (prestation_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_4C50F389E45C554 (prestation_id), INDEX IDX_4C50F38BAD26311 (tag_id), PRIMARY KEY(prestation_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realisation (id INT AUTO_INCREMENT NOT NULL, is_public TINYINT(1) NOT NULL, tarif NUMERIC(10, 2) DEFAULT NULL, is_tarif_public TINYINT(1) DEFAULT NULL, date_realisation DATETIME DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', duration TIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_389B7835E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_prestation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, fullname VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, phone VARCHAR(255) DEFAULT NULL, date_of_birthday DATE DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, cgu TINYINT(1) NOT NULL, is_request_delete TINYINT(1) DEFAULT NULL, stripe_id LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE19EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE9E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id)');
        $this->addSql('ALTER TABLE email_log ADD CONSTRAINT FK_6FB4883E92F8F78 FOREIGN KEY (recipient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE email_verification ADD CONSTRAINT FK_FE22358F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE image_realisation ADD CONSTRAINT FK_25732A80B685E551 FOREIGN KEY (realisation_id) REFERENCES realisation (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D19EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD809EE01F FOREIGN KEY (category_prestation_id) REFERENCES category_prestation (id)');
        $this->addSql('ALTER TABLE prestation_tag ADD CONSTRAINT FK_4C50F389E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prestation_tag ADD CONSTRAINT FK_4C50F38BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE19EB6921');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE9E45C554');
        $this->addSql('ALTER TABLE email_log DROP FOREIGN KEY FK_6FB4883E92F8F78');
        $this->addSql('ALTER TABLE email_verification DROP FOREIGN KEY FK_FE22358F675F31B');
        $this->addSql('ALTER TABLE image_realisation DROP FOREIGN KEY FK_25732A80B685E551');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D3301C60');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D19EB6921');
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FAD809EE01F');
        $this->addSql('ALTER TABLE prestation_tag DROP FOREIGN KEY FK_4C50F389E45C554');
        $this->addSql('ALTER TABLE prestation_tag DROP FOREIGN KEY FK_4C50F38BAD26311');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE category_prestation');
        $this->addSql('DROP TABLE client_agree_term');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE email_log');
        $this->addSql('DROP TABLE email_verification');
        $this->addSql('DROP TABLE holiday');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE image_realisation');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('DROP TABLE prestation_tag');
        $this->addSql('DROP TABLE realisation');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE type_prestation');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
