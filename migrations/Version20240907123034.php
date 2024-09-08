<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240907123034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_categories (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, titre VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, INDEX IDX_DC356481727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_tags (id INT AUTO_INCREMENT NOT NULL, icone VARCHAR(100) DEFAULT NULL, couleur VARCHAR(50) DEFAULT NULL, titre VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories_images (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, comments_id INT DEFAULT NULL, users_id INT NOT NULL, posts_id INT NOT NULL, contenu LONGTEXT NOT NULL, is_reply TINYINT(1) NOT NULL, INDEX IDX_5F9E962A63379586 (comments_id), INDEX IDX_5F9E962A67B3B43D (users_id), INDEX IDX_5F9E962AD5E258C5 (posts_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE galeries (id INT AUTO_INCREMENT NOT NULL, page_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, primary_background_color VARCHAR(10) DEFAULT NULL, primary_title_color VARCHAR(10) DEFAULT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_EB9F215AC4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupes_links (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, afficher_titre TINYINT(1) NOT NULL, parent VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, galerie_id INT NOT NULL, name VARCHAR(255) NOT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, size INT DEFAULT NULL, original_name VARCHAR(255) DEFAULT NULL, ordre INT DEFAULT NULL, mime_type VARCHAR(255) DEFAULT NULL, INDEX IDX_E01FBE6A825396CB (galerie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE links (id INT AUTO_INCREMENT NOT NULL, groupe_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, icone VARCHAR(50) DEFAULT NULL, parent VARCHAR(15) NOT NULL, titre VARCHAR(255) NOT NULL, INDEX IDX_D182A1187A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pages (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, sous_titre VARCHAR(255) DEFAULT NULL, ordre INT NOT NULL, etat VARCHAR(50) NOT NULL, slug VARCHAR(255) NOT NULL, is_page_accueil TINYINT(1) DEFAULT NULL, icone_onglet VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, titre VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, featured_image VARCHAR(255) NOT NULL, INDEX IDX_885DBAFA67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts_blog_categories (posts_id INT NOT NULL, blog_categories_id INT NOT NULL, INDEX IDX_5971066CD5E258C5 (posts_id), INDEX IDX_5971066CD194DEDB (blog_categories_id), PRIMARY KEY(posts_id, blog_categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts_blog_tags (posts_id INT NOT NULL, blog_tags_id INT NOT NULL, INDEX IDX_6D0EAD7AD5E258C5 (posts_id), INDEX IDX_6D0EAD7A618DF237 (blog_tags_id), PRIMARY KEY(posts_id, blog_tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sections_pages (id INT AUTO_INCREMENT NOT NULL, page_id INT NOT NULL, galerie_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E819A827C4663E4 (page_id), UNIQUE INDEX UNIQ_E819A827825396CB (galerie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, icone VARCHAR(50) DEFAULT NULL, couleur VARCHAR(10) DEFAULT NULL, titre VARCHAR(255) NOT NULL, parent VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags_galeries (tags_id INT NOT NULL, galeries_id INT NOT NULL, INDEX IDX_8109BD438D7B4FB4 (tags_id), INDEX IDX_8109BD43EB45F58B (galeries_id), PRIMARY KEY(tags_id, galeries_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, nickname VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_1483A5E9A188FE64 (nickname), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_categories ADD CONSTRAINT FK_DC356481727ACA70 FOREIGN KEY (parent_id) REFERENCES blog_categories (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A63379586 FOREIGN KEY (comments_id) REFERENCES comments (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AD5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE galeries ADD CONSTRAINT FK_EB9F215AC4663E4 FOREIGN KEY (page_id) REFERENCES pages (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A825396CB FOREIGN KEY (galerie_id) REFERENCES galeries (id)');
        $this->addSql('ALTER TABLE links ADD CONSTRAINT FK_D182A1187A45358C FOREIGN KEY (groupe_id) REFERENCES groupes_links (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE posts_blog_categories ADD CONSTRAINT FK_5971066CD5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE posts_blog_categories ADD CONSTRAINT FK_5971066CD194DEDB FOREIGN KEY (blog_categories_id) REFERENCES blog_categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE posts_blog_tags ADD CONSTRAINT FK_6D0EAD7AD5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE posts_blog_tags ADD CONSTRAINT FK_6D0EAD7A618DF237 FOREIGN KEY (blog_tags_id) REFERENCES blog_tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sections_pages ADD CONSTRAINT FK_E819A827C4663E4 FOREIGN KEY (page_id) REFERENCES pages (id)');
        $this->addSql('ALTER TABLE sections_pages ADD CONSTRAINT FK_E819A827825396CB FOREIGN KEY (galerie_id) REFERENCES galeries (id)');
        $this->addSql('ALTER TABLE tags_galeries ADD CONSTRAINT FK_8109BD438D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_galeries ADD CONSTRAINT FK_8109BD43EB45F58B FOREIGN KEY (galeries_id) REFERENCES galeries (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_categories DROP FOREIGN KEY FK_DC356481727ACA70');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A63379586');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A67B3B43D');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AD5E258C5');
        $this->addSql('ALTER TABLE galeries DROP FOREIGN KEY FK_EB9F215AC4663E4');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A825396CB');
        $this->addSql('ALTER TABLE links DROP FOREIGN KEY FK_D182A1187A45358C');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA67B3B43D');
        $this->addSql('ALTER TABLE posts_blog_categories DROP FOREIGN KEY FK_5971066CD5E258C5');
        $this->addSql('ALTER TABLE posts_blog_categories DROP FOREIGN KEY FK_5971066CD194DEDB');
        $this->addSql('ALTER TABLE posts_blog_tags DROP FOREIGN KEY FK_6D0EAD7AD5E258C5');
        $this->addSql('ALTER TABLE posts_blog_tags DROP FOREIGN KEY FK_6D0EAD7A618DF237');
        $this->addSql('ALTER TABLE sections_pages DROP FOREIGN KEY FK_E819A827C4663E4');
        $this->addSql('ALTER TABLE sections_pages DROP FOREIGN KEY FK_E819A827825396CB');
        $this->addSql('ALTER TABLE tags_galeries DROP FOREIGN KEY FK_8109BD438D7B4FB4');
        $this->addSql('ALTER TABLE tags_galeries DROP FOREIGN KEY FK_8109BD43EB45F58B');
        $this->addSql('DROP TABLE blog_categories');
        $this->addSql('DROP TABLE blog_tags');
        $this->addSql('DROP TABLE categories_images');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE galeries');
        $this->addSql('DROP TABLE groupes_links');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE links');
        $this->addSql('DROP TABLE pages');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE posts_blog_categories');
        $this->addSql('DROP TABLE posts_blog_tags');
        $this->addSql('DROP TABLE sections_pages');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE tags_galeries');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
