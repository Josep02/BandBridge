<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603174403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, musician_id INT DEFAULT NULL, organization_id INT DEFAULT NULL, state VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D6C34BD79523AA8A (musician_id), UNIQUE INDEX UNIQ_D6C34BD732C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_D6C34BD79523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_D6C34BD732C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_D6C34BD79523AA8A');
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_D6C34BD732C8A3DE');
        $this->addSql('DROP TABLE invitation');
    }
}
