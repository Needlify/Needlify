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
final class Version20230324130630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Created Lesson table without linked link system';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', course_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(120) NOT NULL, description VARCHAR(500) NOT NULL, content LONGTEXT NOT NULL, views INT NOT NULL, slug VARCHAR(134) NOT NULL, published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', private TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_F87474F3591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3591CC992');
        $this->addSql('DROP TABLE lesson');
    }
}
