<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220910210242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(120) NOT NULL, description VARCHAR(500) NOT NULL, content LONGTEXT NOT NULL, views INT NOT NULL, slug VARCHAR(130) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moodline (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', content TEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', author_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', topic_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', type VARCHAR(255) NOT NULL, INDEX IDX_AF3C6779F675F31B (author_id), INDEX IDX_AF3C67791F55203D (topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_tag (publication_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', tag_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_20D75B4C38B217A7 (publication_id), INDEX IDX_20D75B4CBAD26311 (tag_id), PRIMARY KEY(publication_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BF396750 FOREIGN KEY (id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE moodline ADD CONSTRAINT FK_367251E7BF396750 FOREIGN KEY (id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67791F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('ALTER TABLE publication_tag ADD CONSTRAINT FK_20D75B4C38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication_tag ADD CONSTRAINT FK_20D75B4CBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BF396750');
        $this->addSql('ALTER TABLE moodline DROP FOREIGN KEY FK_367251E7BF396750');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779F675F31B');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67791F55203D');
        $this->addSql('ALTER TABLE publication_tag DROP FOREIGN KEY FK_20D75B4C38B217A7');
        $this->addSql('ALTER TABLE publication_tag DROP FOREIGN KEY FK_20D75B4CBAD26311');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE moodline');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE publication_tag');
    }
}
