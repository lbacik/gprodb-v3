<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329104846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_settings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, landing_page_entity_id VARCHAR(64) DEFAULT NULL, custom_domain_entity_id VARCHAR(64) DEFAULT NULL, newsletter_provider_entity_id VARCHAR(64) DEFAULT NULL, newsletter_provider_config_entity_id VARCHAR(64) DEFAULT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__projects AS SELECT id, owner_id, name, description FROM projects');
        $this->addSql('DROP TABLE projects');
        $this->addSql('CREATE TABLE projects (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, CONSTRAINT FK_5C93B3A47E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO projects (id, owner_id, name, description) SELECT id, owner_id, name, description FROM __temp__projects');
        $this->addSql('DROP TABLE __temp__projects');
        $this->addSql('CREATE INDEX IDX_5C93B3A47E3C61F9 ON projects (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE project_settings');
        $this->addSql('CREATE TEMPORARY TABLE __temp__projects AS SELECT id, owner_id, name, description FROM projects');
        $this->addSql('DROP TABLE projects');
        $this->addSql('CREATE TABLE projects (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, CONSTRAINT FK_5C93B3A47E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO projects (id, owner_id, name, description) SELECT id, owner_id, name, description FROM __temp__projects');
        $this->addSql('DROP TABLE __temp__projects');
        $this->addSql('CREATE INDEX IDX_5C93B3A47E3C61F9 ON projects (owner_id)');
    }
}
