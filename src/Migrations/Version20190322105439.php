<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190322105439 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE meme_downvote');
        $this->addSql('DROP TABLE meme_upvote');
        $this->addSql('ALTER TABLE meme_user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE meme_downvote (meme_id INT NOT NULL, downvote_id INT NOT NULL, INDEX IDX_95114176157512BC (downvote_id), INDEX IDX_95114176DB6EC45D (meme_id), PRIMARY KEY(meme_id, downvote_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE meme_upvote (meme_id INT NOT NULL, upvote_id INT NOT NULL, INDEX IDX_FEDAD2501FDD0626 (upvote_id), INDEX IDX_FEDAD250DB6EC45D (meme_id), PRIMARY KEY(meme_id, upvote_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE meme_downvote ADD CONSTRAINT FK_95114176157512BC FOREIGN KEY (downvote_id) REFERENCES downvote (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meme_downvote ADD CONSTRAINT FK_95114176DB6EC45D FOREIGN KEY (meme_id) REFERENCES meme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meme_upvote ADD CONSTRAINT FK_FEDAD2501FDD0626 FOREIGN KEY (upvote_id) REFERENCES upvote (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meme_upvote ADD CONSTRAINT FK_FEDAD250DB6EC45D FOREIGN KEY (meme_id) REFERENCES meme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meme_user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
