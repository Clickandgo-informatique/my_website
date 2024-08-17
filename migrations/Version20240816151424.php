<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240816151424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE links ADD groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE links ADD CONSTRAINT FK_D182A1187A45358C FOREIGN KEY (groupe_id) REFERENCES groupes_links (id)');
        $this->addSql('CREATE INDEX IDX_D182A1187A45358C ON links (groupe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE links DROP FOREIGN KEY FK_D182A1187A45358C');
        $this->addSql('DROP INDEX IDX_D182A1187A45358C ON links');
        $this->addSql('ALTER TABLE links DROP groupe_id');
    }
}
