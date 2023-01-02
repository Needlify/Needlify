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
final class Version20221028200644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Updated slug length on article and classifier and updated message length on event';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE slug slug VARCHAR(134) NOT NULL');
        $this->addSql('ALTER TABLE classifier CHANGE slug slug VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE message message VARCHAR(240) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classifier CHANGE slug slug VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE message message VARCHAR(250) NOT NULL');
        $this->addSql('ALTER TABLE article CHANGE slug slug VARCHAR(140) NOT NULL');
    }
}
