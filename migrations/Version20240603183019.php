<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603183019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invitation DROP INDEX UNIQ_D6C34BD79523AA8A, ADD INDEX IDX_F11D61A29523AA8A (musician_id)');
        $this->addSql('ALTER TABLE invitation DROP INDEX UNIQ_D6C34BD732C8A3DE, ADD INDEX IDX_F11D61A232C8A3DE (organization_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invitation DROP INDEX IDX_F11D61A29523AA8A, ADD UNIQUE INDEX UNIQ_D6C34BD79523AA8A (musician_id)');
        $this->addSql('ALTER TABLE invitation DROP INDEX IDX_F11D61A232C8A3DE, ADD UNIQUE INDEX UNIQ_D6C34BD732C8A3DE (organization_id)');
    }
}
