<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210304162342 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice_headers (id INT AUTO_INCREMENT NOT NULL, location_id INT NOT NULL, date DATE DEFAULT NULL, status VARCHAR(45) DEFAULT NULL, INDEX IDX_5DA795664D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice_lines (id INT AUTO_INCREMENT NOT NULL, invoice_header_id INT NOT NULL, description VARCHAR(255) NOT NULL, value NUMERIC(10, 2) NOT NULL, INDEX IDX_72DBDC234B56EE44 (invoice_header_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locations (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoice_headers ADD CONSTRAINT FK_5DA795664D218E FOREIGN KEY (location_id) REFERENCES locations (id)');
        $this->addSql('ALTER TABLE invoice_lines ADD CONSTRAINT FK_72DBDC234B56EE44 FOREIGN KEY (invoice_header_id) REFERENCES invoice_headers (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice_lines DROP FOREIGN KEY FK_72DBDC234B56EE44');
        $this->addSql('ALTER TABLE invoice_headers DROP FOREIGN KEY FK_5DA795664D218E');
        $this->addSql('DROP TABLE invoice_headers');
        $this->addSql('DROP TABLE invoice_lines');
        $this->addSql('DROP TABLE locations');
    }
}
