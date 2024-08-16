<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240812100914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tags_galeries (tags_id INT NOT NULL, galeries_id INT NOT NULL, INDEX IDX_8109BD438D7B4FB4 (tags_id), INDEX IDX_8109BD43EB45F58B (galeries_id), PRIMARY KEY(tags_id, galeries_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tags_galeries ADD CONSTRAINT FK_8109BD438D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_galeries ADD CONSTRAINT FK_8109BD43EB45F58B FOREIGN KEY (galeries_id) REFERENCES galeries (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tags_galeries DROP FOREIGN KEY FK_8109BD438D7B4FB4');
        $this->addSql('ALTER TABLE tags_galeries DROP FOREIGN KEY FK_8109BD43EB45F58B');
        $this->addSql('DROP TABLE tags_galeries');
    }
}
