<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190331171858 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE meme CHANGE uploaded_at uploaded_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE meme_user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE upvote ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE upvote ADD CONSTRAINT FK_68AB8766B03A8386 FOREIGN KEY (created_by_id) REFERENCES meme_user (id)');
        $this->addSql('CREATE INDEX IDX_68AB8766B03A8386 ON upvote (created_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE meme CHANGE uploaded_at uploaded_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE meme_user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE upvote DROP FOREIGN KEY FK_68AB8766B03A8386');
        $this->addSql('DROP INDEX IDX_68AB8766B03A8386 ON upvote');
        $this->addSql('ALTER TABLE upvote DROP created_by_id');
    }
}
