<?php

declare(strict_types=1);

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221126170556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP updated_at');
        $this->addSql('ALTER TABLE classifier ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE thread ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user DROP updated_at');
        $this->addSql('ALTER TABLE classifier DROP updated_at');
        $this->addSql('ALTER TABLE thread DROP updated_at');
    }
}
