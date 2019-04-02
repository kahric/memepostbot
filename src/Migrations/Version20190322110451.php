<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190322110451 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE downvote_meme_user DROP FOREIGN KEY FK_57D82239157512BC');
        $this->addSql('ALTER TABLE upvote_meme_user DROP FOREIGN KEY FK_ABCD7FF51FDD0626');
        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vote_meme (vote_id INT NOT NULL, meme_id INT NOT NULL, INDEX IDX_FCFD880572DCDAFC (vote_id), INDEX IDX_FCFD8805DB6EC45D (meme_id), PRIMARY KEY(vote_id, meme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vote_meme ADD CONSTRAINT FK_FCFD880572DCDAFC FOREIGN KEY (vote_id) REFERENCES vote (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vote_meme ADD CONSTRAINT FK_FCFD8805DB6EC45D FOREIGN KEY (meme_id) REFERENCES meme (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE downvote');
        $this->addSql('DROP TABLE downvote_meme_user');
        $this->addSql('DROP TABLE upvote');
        $this->addSql('DROP TABLE upvote_meme_user');
        $this->addSql('ALTER TABLE meme_user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vote_meme DROP FOREIGN KEY FK_FCFD880572DCDAFC');
        $this->addSql('CREATE TABLE downvote (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE downvote_meme_user (downvote_id INT NOT NULL, meme_user_id INT NOT NULL, INDEX IDX_57D8223968423759 (meme_user_id), INDEX IDX_57D82239157512BC (downvote_id), PRIMARY KEY(downvote_id, meme_user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE upvote (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE upvote_meme_user (upvote_id INT NOT NULL, meme_user_id INT NOT NULL, INDEX IDX_ABCD7FF568423759 (meme_user_id), INDEX IDX_ABCD7FF51FDD0626 (upvote_id), PRIMARY KEY(upvote_id, meme_user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE downvote_meme_user ADD CONSTRAINT FK_57D82239157512BC FOREIGN KEY (downvote_id) REFERENCES downvote (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE downvote_meme_user ADD CONSTRAINT FK_57D8223968423759 FOREIGN KEY (meme_user_id) REFERENCES meme_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE upvote_meme_user ADD CONSTRAINT FK_ABCD7FF51FDD0626 FOREIGN KEY (upvote_id) REFERENCES upvote (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE upvote_meme_user ADD CONSTRAINT FK_ABCD7FF568423759 FOREIGN KEY (meme_user_id) REFERENCES meme_user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE vote');
        $this->addSql('DROP TABLE vote_meme');
        $this->addSql('ALTER TABLE meme_user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
