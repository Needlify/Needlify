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
final class Version20230212181935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Updated datetime columns (mutable to immutable)';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classifier CHANGE last_use_at last_use_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE newsletter_account CHANGE verified_at verified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', CHANGE subscribed_at subscribed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', CHANGE last_retry_at last_retry_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE thread CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE newsletter_account CHANGE verified_at verified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE subscribed_at subscribed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE last_retry_at last_retry_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE classifier CHANGE last_use_at last_use_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE thread CHANGE updated_at updated_at DATETIME NOT NULL');
    }
}
