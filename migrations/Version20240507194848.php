<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507194848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE valoration (id INT AUTO_INCREMENT NOT NULL, musician_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_A0F38FD29523AA8A (musician_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE valoration ADD CONSTRAINT FK_A0F38FD29523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id)');
        $this->addSql('ALTER TABLE musician ADD instrument_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE musician ADD CONSTRAINT FK_31714127CF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('CREATE INDEX IDX_31714127CF11D9C ON musician (instrument_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE valoration DROP FOREIGN KEY FK_A0F38FD29523AA8A');
        $this->addSql('DROP TABLE valoration');
        $this->addSql('ALTER TABLE musician DROP FOREIGN KEY FK_31714127CF11D9C');
        $this->addSql('DROP INDEX IDX_31714127CF11D9C ON musician');
        $this->addSql('ALTER TABLE musician DROP instrument_id');
    }
}