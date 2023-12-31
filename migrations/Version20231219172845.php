<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231219172845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artist DROP avatar_file, CHANGE avatar avatar VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE image DROP file');
        $this->addSql('ALTER TABLE video DROP file');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artist ADD avatar_file VARCHAR(255) DEFAULT NULL, CHANGE avatar avatar VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD file VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE video ADD file VARCHAR(255) NOT NULL');
    }
}
