<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190331171016 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE upvote (id INT AUTO_INCREMENT NOT NULL, meme_id INT NOT NULL, INDEX IDX_68AB8766DB6EC45D (meme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE upvote ADD CONSTRAINT FK_68AB8766DB6EC45D FOREIGN KEY (meme_id) REFERENCES meme (id)');
        $this->addSql('ALTER TABLE meme CHANGE uploaded_at uploaded_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE meme_user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE upvote');
        $this->addSql('ALTER TABLE meme CHANGE uploaded_at uploaded_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE meme_user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
