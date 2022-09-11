<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220911122350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the classifier tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classifier (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_use_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_449272F75E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP INDEX UNIQ_389B7835E237E06 ON tag');
        $this->addSql('ALTER TABLE tag DROP name, DROP created_at, DROP last_use_at');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783BF396750 FOREIGN KEY (id) REFERENCES classifier (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX UNIQ_9D40DE1B5E237E06 ON topic');
        $this->addSql('ALTER TABLE topic DROP name, DROP created_at, DROP last_use_at');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1BBF396750 FOREIGN KEY (id) REFERENCES classifier (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783BF396750');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1BBF396750');
        $this->addSql('DROP TABLE classifier');
        $this->addSql('ALTER TABLE tag ADD name VARCHAR(50) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD last_use_at DATETIME NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B7835E237E06 ON tag (name)');
        $this->addSql('ALTER TABLE topic ADD name VARCHAR(50) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD last_use_at DATETIME NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9D40DE1B5E237E06 ON topic (name)');
    }
}
