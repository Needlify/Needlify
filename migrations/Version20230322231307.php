<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230322231307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Created the Document table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(120) NOT NULL, description VARCHAR(500) NOT NULL, content LONGTEXT NOT NULL, views INT NOT NULL, slug VARCHAR(134) NOT NULL, license TINYINT(1) DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76BF396750 FOREIGN KEY (id) REFERENCES thread (id) ON DELETE CASCADE');
        $this->addSql('INSERT INTO document (id, title, description, content, views, slug, license) SELECT id, title, description, content, views, slug, license FROM article');
        $this->addSql('ALTER TABLE article DROP title, DROP description, DROP content, DROP views, DROP slug, DROP license');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE article ADD title VARCHAR(120) NOT NULL, ADD description VARCHAR(500) NOT NULL, ADD content LONGTEXT NOT NULL, ADD views INT NOT NULL, ADD slug VARCHAR(134) NOT NULL, ADD license TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('UPDATE article, document SET article.title = document.title WHERE article.id = document.id');
        $this->addSql('UPDATE article, document SET article.description = document.description WHERE article.id = document.id');
        $this->addSql('UPDATE article, document SET article.content = document.content WHERE article.id = document.id');
        $this->addSql('UPDATE article, document SET article.views = document.views WHERE article.id = document.id');
        $this->addSql('UPDATE article, document SET article.slug = document.slug WHERE article.id = document.id');
        $this->addSql('UPDATE article, document SET article.license = document.license WHERE article.id = document.id');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76BF396750');
        $this->addSql('DROP TABLE document');
    }
}
