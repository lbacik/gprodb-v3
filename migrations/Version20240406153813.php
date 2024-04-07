<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240406153813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__project_settings AS SELECT id, landing_page_entity_id, custom_domain_entity_id, newsletter_provider_entity_id, newsletter_provider_config_entity_id FROM project_settings');
        $this->addSql('DROP TABLE project_settings');
        $this->addSql('CREATE TABLE project_settings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, landing_page_id INTEGER DEFAULT NULL, landing_page_entity_id VARCHAR(64) DEFAULT NULL, custom_domain_entity_id VARCHAR(64) DEFAULT NULL, newsletter_provider_entity_id VARCHAR(64) DEFAULT NULL, newsletter_provider_config_entity_id VARCHAR(64) DEFAULT NULL, CONSTRAINT FK_D80B2B1EDF122DC5 FOREIGN KEY (landing_page_id) REFERENCES landing_page (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO project_settings (id, landing_page_entity_id, custom_domain_entity_id, newsletter_provider_entity_id, newsletter_provider_config_entity_id) SELECT id, landing_page_entity_id, custom_domain_entity_id, newsletter_provider_entity_id, newsletter_provider_config_entity_id FROM __temp__project_settings');
        $this->addSql('DROP TABLE __temp__project_settings');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D80B2B1EDF122DC5 ON project_settings (landing_page_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__projects AS SELECT id, owner_id, name, description FROM projects');
        $this->addSql('DROP TABLE projects');
        $this->addSql('CREATE TABLE projects (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, settings_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, CONSTRAINT FK_5C93B3A47E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5C93B3A459949888 FOREIGN KEY (settings_id) REFERENCES project_settings (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO projects (id, owner_id, name, description) SELECT id, owner_id, name, description FROM __temp__projects');
        $this->addSql('DROP TABLE __temp__projects');
        $this->addSql('CREATE INDEX IDX_5C93B3A47E3C61F9 ON projects (owner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5C93B3A459949888 ON projects (settings_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__project_settings AS SELECT id, landing_page_entity_id, custom_domain_entity_id, newsletter_provider_entity_id, newsletter_provider_config_entity_id FROM project_settings');
        $this->addSql('DROP TABLE project_settings');
        $this->addSql('CREATE TABLE project_settings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, landing_page_entity_id VARCHAR(64) DEFAULT NULL, custom_domain_entity_id VARCHAR(64) DEFAULT NULL, newsletter_provider_entity_id VARCHAR(64) DEFAULT NULL, newsletter_provider_config_entity_id VARCHAR(64) DEFAULT NULL)');
        $this->addSql('INSERT INTO project_settings (id, landing_page_entity_id, custom_domain_entity_id, newsletter_provider_entity_id, newsletter_provider_config_entity_id) SELECT id, landing_page_entity_id, custom_domain_entity_id, newsletter_provider_entity_id, newsletter_provider_config_entity_id FROM __temp__project_settings');
        $this->addSql('DROP TABLE __temp__project_settings');
        $this->addSql('CREATE TEMPORARY TABLE __temp__projects AS SELECT id, owner_id, name, description FROM projects');
        $this->addSql('DROP TABLE projects');
        $this->addSql('CREATE TABLE projects (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, CONSTRAINT FK_5C93B3A47E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO projects (id, owner_id, name, description) SELECT id, owner_id, name, description FROM __temp__projects');
        $this->addSql('DROP TABLE __temp__projects');
        $this->addSql('CREATE INDEX IDX_5C93B3A47E3C61F9 ON projects (owner_id)');
    }
}
