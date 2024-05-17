<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240509232718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classification (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE details (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, required_instrument_id INT DEFAULT NULL, min_payment DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, INDEX IDX_72260B8A71F7E88B (event_id), INDEX IDX_72260B8A39B4FAB6 (required_instrument_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, organization_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, created DATE NOT NULL, location VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA732C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instrument (id INT AUTO_INCREMENT NOT NULL, classification_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_3CBF69DD2A86559F (classification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE musician (id INT AUTO_INCREMENT NOT NULL, instrument_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_31714127CF11D9C (instrument_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE musician_class (id INT AUTO_INCREMENT NOT NULL, musician_id INT DEFAULT NULL, organization_id INT DEFAULT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_EE63F92A9523AA8A (musician_id), INDEX IDX_EE63F92A32C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization (id INT AUTO_INCREMENT NOT NULL, organization_type_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_C1EE637C89E04D0 (organization_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation_request (id INT AUTO_INCREMENT NOT NULL, musician_id INT DEFAULT NULL, event_id INT DEFAULT NULL, state VARCHAR(255) NOT NULL, INDEX IDX_70E93E5E9523AA8A (musician_id), INDEX IDX_70E93E5E71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE valoration (id INT AUTO_INCREMENT NOT NULL, musician_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_A0F38FD29523AA8A (musician_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE details ADD CONSTRAINT FK_72260B8A71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE details ADD CONSTRAINT FK_72260B8A39B4FAB6 FOREIGN KEY (required_instrument_id) REFERENCES instrument (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA732C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE instrument ADD CONSTRAINT FK_3CBF69DD2A86559F FOREIGN KEY (classification_id) REFERENCES classification (id)');
        $this->addSql('ALTER TABLE musician ADD CONSTRAINT FK_31714127CF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('ALTER TABLE musician_class ADD CONSTRAINT FK_EE63F92A9523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id)');
        $this->addSql('ALTER TABLE musician_class ADD CONSTRAINT FK_EE63F92A32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C89E04D0 FOREIGN KEY (organization_type_id) REFERENCES organization_type (id)');
        $this->addSql('ALTER TABLE participation_request ADD CONSTRAINT FK_70E93E5E9523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id)');
        $this->addSql('ALTER TABLE participation_request ADD CONSTRAINT FK_70E93E5E71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE valoration ADD CONSTRAINT FK_A0F38FD29523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE details DROP FOREIGN KEY FK_72260B8A71F7E88B');
        $this->addSql('ALTER TABLE details DROP FOREIGN KEY FK_72260B8A39B4FAB6');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA732C8A3DE');
        $this->addSql('ALTER TABLE instrument DROP FOREIGN KEY FK_3CBF69DD2A86559F');
        $this->addSql('ALTER TABLE musician DROP FOREIGN KEY FK_31714127CF11D9C');
        $this->addSql('ALTER TABLE musician_class DROP FOREIGN KEY FK_EE63F92A9523AA8A');
        $this->addSql('ALTER TABLE musician_class DROP FOREIGN KEY FK_EE63F92A32C8A3DE');
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637C89E04D0');
        $this->addSql('ALTER TABLE participation_request DROP FOREIGN KEY FK_70E93E5E9523AA8A');
        $this->addSql('ALTER TABLE participation_request DROP FOREIGN KEY FK_70E93E5E71F7E88B');
        $this->addSql('ALTER TABLE valoration DROP FOREIGN KEY FK_A0F38FD29523AA8A');
        $this->addSql('DROP TABLE classification');
        $this->addSql('DROP TABLE details');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE instrument');
        $this->addSql('DROP TABLE musician');
        $this->addSql('DROP TABLE musician_class');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE organization_type');
        $this->addSql('DROP TABLE participation_request');
        $this->addSql('DROP TABLE valoration');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
