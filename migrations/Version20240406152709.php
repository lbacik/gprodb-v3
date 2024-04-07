<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240406152709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE landing_page (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, hero_id INTEGER DEFAULT NULL, about_id INTEGER DEFAULT NULL, name VARCHAR(64) NOT NULL, title VARCHAR(255) DEFAULT NULL, contact BOOLEAN NOT NULL, contact_info CLOB DEFAULT NULL, newsletter BOOLEAN NOT NULL, newsletter_info CLOB DEFAULT NULL, CONSTRAINT FK_87A7C89945B0BCD FOREIGN KEY (hero_id) REFERENCES landing_page_hero (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_87A7C899D087DB59 FOREIGN KEY (about_id) REFERENCES landing_page_about (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_87A7C89945B0BCD ON landing_page (hero_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_87A7C899D087DB59 ON landing_page (about_id)');
        $this->addSql('CREATE TABLE landing_page_about (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, column_left CLOB DEFAULT NULL, column_right CLOB DEFAULT NULL)');
        $this->addSql('CREATE TABLE landing_page_hero (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(32) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE landing_page');
        $this->addSql('DROP TABLE landing_page_about');
        $this->addSql('DROP TABLE landing_page_hero');
    }
}
