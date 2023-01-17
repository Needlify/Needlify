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
final class Version20221029184923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update field name from message to content';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE message content VARCHAR(240) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE content message VARCHAR(240) NOT NULL');
    }
}
