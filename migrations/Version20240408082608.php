<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408082608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_settings ADD COLUMN domain VARCHAR(128) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__project_settings AS SELECT id, landing_page_id, landing_page_entity_id, custom_domain_entity_id, newsletter_provider_entity_id, newsletter_provider_config_entity_id FROM project_settings');
        $this->addSql('DROP TABLE project_settings');
        $this->addSql('CREATE TABLE project_settings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, landing_page_id INTEGER DEFAULT NULL, landing_page_entity_id VARCHAR(64) DEFAULT NULL, custom_domain_entity_id VARCHAR(64) DEFAULT NULL, newsletter_provider_entity_id VARCHAR(64) DEFAULT NULL, newsletter_provider_config_entity_id VARCHAR(64) DEFAULT NULL, CONSTRAINT FK_D80B2B1EDF122DC5 FOREIGN KEY (landing_page_id) REFERENCES landing_page (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO project_settings (id, landing_page_id, landing_page_entity_id, custom_domain_entity_id, newsletter_provider_entity_id, newsletter_provider_config_entity_id) SELECT id, landing_page_id, landing_page_entity_id, custom_domain_entity_id, newsletter_provider_entity_id, newsletter_provider_config_entity_id FROM __temp__project_settings');
        $this->addSql('DROP TABLE __temp__project_settings');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D80B2B1EDF122DC5 ON project_settings (landing_page_id)');
    }
}
