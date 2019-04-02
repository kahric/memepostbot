<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190322115353 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE meme DROP FOREIGN KEY FK_4B9F793455B127A4');
        $this->addSql('DROP INDEX IDX_4B9F793455B127A4 ON meme');
        $this->addSql('ALTER TABLE meme CHANGE added_by_id created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE meme ADD CONSTRAINT FK_4B9F7934B03A8386 FOREIGN KEY (created_by_id) REFERENCES meme_user (id)');
        $this->addSql('CREATE INDEX IDX_4B9F7934B03A8386 ON meme (created_by_id)');
        $this->addSql('ALTER TABLE meme_user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE meme DROP FOREIGN KEY FK_4B9F7934B03A8386');
        $this->addSql('DROP INDEX IDX_4B9F7934B03A8386 ON meme');
        $this->addSql('ALTER TABLE meme CHANGE created_by_id added_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE meme ADD CONSTRAINT FK_4B9F793455B127A4 FOREIGN KEY (added_by_id) REFERENCES meme_user (id)');
        $this->addSql('CREATE INDEX IDX_4B9F793455B127A4 ON meme (added_by_id)');
        $this->addSql('ALTER TABLE meme_user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
