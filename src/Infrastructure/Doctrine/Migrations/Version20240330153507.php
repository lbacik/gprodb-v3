<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240330153507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__links AS SELECT id, project_id, name, url FROM links');
        $this->addSql('DROP TABLE links');
        $this->addSql('CREATE TABLE links (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, project_id INTEGER NOT NULL, name VARCHAR(64) DEFAULT NULL, url VARCHAR(255) NOT NULL, CONSTRAINT FK_D182A118166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO links (id, project_id, name, url) SELECT id, project_id, name, url FROM __temp__links');
        $this->addSql('DROP TABLE __temp__links');
        $this->addSql('CREATE INDEX IDX_D182A118166D1F9C ON links (project_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__links AS SELECT id, project_id, name, url FROM links');
        $this->addSql('DROP TABLE links');
        $this->addSql('CREATE TABLE links (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, project_id INTEGER NOT NULL, name VARCHAR(64) NOT NULL, url VARCHAR(255) NOT NULL, CONSTRAINT FK_D182A118166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO links (id, project_id, name, url) SELECT id, project_id, name, url FROM __temp__links');
        $this->addSql('DROP TABLE __temp__links');
        $this->addSql('CREATE INDEX IDX_D182A118166D1F9C ON links (project_id)');
    }
}
