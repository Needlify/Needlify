<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230324131228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson ADD next_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD previous_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3AA23F6C8 FOREIGN KEY (next_id) REFERENCES lesson (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F32DE62210 FOREIGN KEY (previous_id) REFERENCES lesson (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F87474F3AA23F6C8 ON lesson (next_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F87474F32DE62210 ON lesson (previous_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3AA23F6C8');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F32DE62210');
        $this->addSql('DROP INDEX UNIQ_F87474F3AA23F6C8 ON lesson');
        $this->addSql('DROP INDEX UNIQ_F87474F32DE62210 ON lesson');
        $this->addSql('ALTER TABLE lesson DROP next_id, DROP previous_id');
    }
}
