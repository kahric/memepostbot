<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190322114901 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE vote_meme');
        $this->addSql('ALTER TABLE meme_user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE vote ADD meme_id INT NOT NULL, ADD created_by_id INT NOT NULL, ADD value TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564DB6EC45D FOREIGN KEY (meme_id) REFERENCES meme (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564B03A8386 FOREIGN KEY (created_by_id) REFERENCES meme_user (id)');
        $this->addSql('CREATE INDEX IDX_5A108564DB6EC45D ON vote (meme_id)');
        $this->addSql('CREATE INDEX IDX_5A108564B03A8386 ON vote (created_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vote_meme (vote_id INT NOT NULL, meme_id INT NOT NULL, INDEX IDX_FCFD8805DB6EC45D (meme_id), INDEX IDX_FCFD880572DCDAFC (vote_id), PRIMARY KEY(vote_id, meme_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE vote_meme ADD CONSTRAINT FK_FCFD880572DCDAFC FOREIGN KEY (vote_id) REFERENCES vote (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vote_meme ADD CONSTRAINT FK_FCFD8805DB6EC45D FOREIGN KEY (meme_id) REFERENCES meme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meme_user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564DB6EC45D');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564B03A8386');
        $this->addSql('DROP INDEX IDX_5A108564DB6EC45D ON vote');
        $this->addSql('DROP INDEX IDX_5A108564B03A8386 ON vote');
        $this->addSql('ALTER TABLE vote DROP meme_id, DROP created_by_id, DROP value');
    }
}
