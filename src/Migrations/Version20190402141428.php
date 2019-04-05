<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190402141428 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE meme (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, caption VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, uploaded TINYINT(1) NOT NULL, uploaded_at DATETIME DEFAULT NULL, INDEX IDX_4B9F7934B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meme_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_77E23E01F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE upvote (id INT AUTO_INCREMENT NOT NULL, meme_id INT NOT NULL, created_by_id INT NOT NULL, INDEX IDX_68AB8766DB6EC45D (meme_id), INDEX IDX_68AB8766B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meme ADD CONSTRAINT FK_4B9F7934B03A8386 FOREIGN KEY (created_by_id) REFERENCES meme_user (id)');
        $this->addSql('ALTER TABLE upvote ADD CONSTRAINT FK_68AB8766DB6EC45D FOREIGN KEY (meme_id) REFERENCES meme (id)');
        $this->addSql('ALTER TABLE upvote ADD CONSTRAINT FK_68AB8766B03A8386 FOREIGN KEY (created_by_id) REFERENCES meme_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE upvote DROP FOREIGN KEY FK_68AB8766DB6EC45D');
        $this->addSql('ALTER TABLE meme DROP FOREIGN KEY FK_4B9F7934B03A8386');
        $this->addSql('ALTER TABLE upvote DROP FOREIGN KEY FK_68AB8766B03A8386');
        $this->addSql('DROP TABLE meme');
        $this->addSql('DROP TABLE meme_user');
        $this->addSql('DROP TABLE upvote');
    }
}
