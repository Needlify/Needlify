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
final class Version20230211223628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added the banner table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE banner (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', content VARCHAR(1600) NOT NULL, started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', ended_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', priority SMALLINT NOT NULL, type VARCHAR(20) NOT NULL, title VARCHAR(255) DEFAULT NULL, link VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE banner');
    }
}
