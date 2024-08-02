<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240801102020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sections_pages ADD galerie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sections_pages ADD CONSTRAINT FK_E819A827825396CB FOREIGN KEY (galerie_id) REFERENCES galeries (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E819A827825396CB ON sections_pages (galerie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sections_pages DROP FOREIGN KEY FK_E819A827825396CB');
        $this->addSql('DROP INDEX UNIQ_E819A827825396CB ON sections_pages');
        $this->addSql('ALTER TABLE sections_pages DROP galerie_id');
    }
}
