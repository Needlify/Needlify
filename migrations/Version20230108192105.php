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
final class Version20230108192105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the newsletter account table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE newsletter_account (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(180) NOT NULL, is_verified TINYINT(1) NOT NULL, verified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', subscribed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_enabled TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_728CF5CBE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE newsletter_account');
    }
}
