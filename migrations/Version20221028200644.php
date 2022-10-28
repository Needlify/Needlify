<?php

declare(strict_types=1);

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
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE slug slug VARCHAR(134) NOT NULL');
        $this->addSql('ALTER TABLE classifier CHANGE slug slug VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE message message VARCHAR(250) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classifier CHANGE slug slug VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE message message VARCHAR(350) NOT NULL');
        $this->addSql('ALTER TABLE article CHANGE slug slug VARCHAR(140) NOT NULL');
    }
}
