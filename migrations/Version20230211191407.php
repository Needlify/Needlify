<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230211191407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE banner (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', content VARCHAR(1000) NOT NULL, started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', ended_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', priority SMALLINT NOT NULL, type ENUM(\'info\', \'event\') NOT NULL COMMENT \'(DC2Enum:1bf4a2058dab768966baf9321e707890)(DC2Type:banner_enum_type)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE banner');
    }
}
