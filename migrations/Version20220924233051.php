<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
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
final class Version20220924233051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Refactoring DB tables. Create the Event and abstract Thread tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', message VARCHAR(350) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', author_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', type VARCHAR(255) NOT NULL, INDEX IDX_31204C83F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7BF396750 FOREIGN KEY (id) REFERENCES thread (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BF396750');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BF396750 FOREIGN KEY (id) REFERENCES thread (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE moodline DROP FOREIGN KEY FK_367251E7BF396750');
        $this->addSql('ALTER TABLE moodline ADD CONSTRAINT FK_367251E7BF396750 FOREIGN KEY (id) REFERENCES thread (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779F675F31B');
        $this->addSql('DROP INDEX IDX_AF3C6779F675F31B ON publication');
        $this->addSql('ALTER TABLE publication DROP author_id, DROP published_at, DROP type');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779BF396750 FOREIGN KEY (id) REFERENCES thread (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BF396750');
        $this->addSql('ALTER TABLE moodline DROP FOREIGN KEY FK_367251E7BF396750');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779BF396750');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7BF396750');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83F675F31B');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE thread');
        $this->addSql('ALTER TABLE moodline DROP FOREIGN KEY FK_367251E7BF396750');
        $this->addSql('ALTER TABLE moodline ADD CONSTRAINT FK_367251E7BF396750 FOREIGN KEY (id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BF396750');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BF396750 FOREIGN KEY (id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication ADD author_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AF3C6779F675F31B ON publication (author_id)');
    }
}
