<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250508162033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Addition of is_thumbnail columns on image and video; modification user_id on post (NULL → NOT NULL)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE image ADD is_thumbnail TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE video ADD is_thumbnail TINYINT(1) NOT NULL');
        // Drop FK on post.user_id
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        // Change column to NOT NULL
        $this->addSql('ALTER TABLE post CHANGE user_id user_id INT NOT NULL');
        // Re-add FK with ON DELETE CASCADE
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE image DROP is_thumbnail');
        $this->addSql('ALTER TABLE video DROP is_thumbnail');
        // Drop FK before reverting column
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        // Revert column to DEFAULT NULL
        $this->addSql('ALTER TABLE post CHANGE user_id user_id INT DEFAULT NULL');
        // Re-add FK with ON DELETE CASCADE (same as before)
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }
}
