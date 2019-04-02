<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190331165528 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE vote');
        $this->addSql('ALTER TABLE meme CHANGE uploaded_at uploaded_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE meme_user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, meme_id INT NOT NULL, created_by_id INT NOT NULL, value VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_5A108564DB6EC45D (meme_id), INDEX IDX_5A108564B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564B03A8386 FOREIGN KEY (created_by_id) REFERENCES meme_user (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564DB6EC45D FOREIGN KEY (meme_id) REFERENCES meme (id)');
        $this->addSql('ALTER TABLE meme CHANGE uploaded_at uploaded_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE meme_user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
