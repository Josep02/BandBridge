<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240606162306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation_request ADD detail_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participation_request ADD CONSTRAINT FK_70E93E5ED8D003BB FOREIGN KEY (detail_id) REFERENCES details (id)');
        $this->addSql('CREATE INDEX IDX_70E93E5ED8D003BB ON participation_request (detail_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation_request DROP FOREIGN KEY FK_70E93E5ED8D003BB');
        $this->addSql('DROP INDEX IDX_70E93E5ED8D003BB ON participation_request');
        $this->addSql('ALTER TABLE participation_request DROP detail_id');
    }
}
