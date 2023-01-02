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
final class Version20221029115953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Updated unique index on classifier table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_449272F75E237E06 ON classifier');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_449272F7989D9B62 ON classifier (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_449272F75E237E06 ON classifier (name)');
        $this->addSql('DROP INDEX UNIQ_449272F7989D9B62 ON classifier');
    }
}
