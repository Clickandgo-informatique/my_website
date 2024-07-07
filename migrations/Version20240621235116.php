<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240621235116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sections_pages ADD page_id INT NOT NULL');
        $this->addSql('ALTER TABLE sections_pages ADD CONSTRAINT FK_E819A827C4663E4 FOREIGN KEY (page_id) REFERENCES pages (id)');
        $this->addSql('CREATE INDEX IDX_E819A827C4663E4 ON sections_pages (page_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sections_pages DROP FOREIGN KEY FK_E819A827C4663E4');
        $this->addSql('DROP INDEX IDX_E819A827C4663E4 ON sections_pages');
        $this->addSql('ALTER TABLE sections_pages DROP page_id');
    }
}
