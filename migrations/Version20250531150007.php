<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250531150007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change artist.description and category.description lengths, and set category.user_id to NOT NULL (with FK adjustments)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE artist CHANGE description description VARCHAR(1000) DEFAULT NULL');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1A76ED395');
        $this->addSql('ALTER TABLE category CHANGE user_id user_id INT NOT NULL, CHANGE description description VARCHAR(500) NOT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1A76ED395');
        $this->addSql('ALTER TABLE category CHANGE user_id user_id INT DEFAULT NULL, CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist CHANGE description description VARCHAR(255) DEFAULT NULL');
    }
}
