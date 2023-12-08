<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231208191406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artist (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, avatar_file VARCHAR(255) DEFAULT NULL, twitch VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_post (category_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_D11116CA12469DE2 (category_id), INDEX IDX_D11116CA4B89032C (post_id), PRIMARY KEY(category_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, file VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, posted_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_artist (post_id INT NOT NULL, artist_id INT NOT NULL, INDEX IDX_317B72E24B89032C (post_id), INDEX IDX_317B72E2B7970CF8 (artist_id), PRIMARY KEY(post_id, artist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_image (post_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_522688B04B89032C (post_id), INDEX IDX_522688B03DA5256D (image_id), PRIMARY KEY(post_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_video (post_id INT NOT NULL, video_id INT NOT NULL, INDEX IDX_EBDC56C34B89032C (post_id), INDEX IDX_EBDC56C329C1004E (video_id), PRIMARY KEY(post_id, video_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, file VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_post ADD CONSTRAINT FK_D11116CA12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_post ADD CONSTRAINT FK_D11116CA4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_artist ADD CONSTRAINT FK_317B72E24B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_artist ADD CONSTRAINT FK_317B72E2B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_image ADD CONSTRAINT FK_522688B04B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_image ADD CONSTRAINT FK_522688B03DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_video ADD CONSTRAINT FK_EBDC56C34B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_video ADD CONSTRAINT FK_EBDC56C329C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_post DROP FOREIGN KEY FK_D11116CA12469DE2');
        $this->addSql('ALTER TABLE category_post DROP FOREIGN KEY FK_D11116CA4B89032C');
        $this->addSql('ALTER TABLE post_artist DROP FOREIGN KEY FK_317B72E24B89032C');
        $this->addSql('ALTER TABLE post_artist DROP FOREIGN KEY FK_317B72E2B7970CF8');
        $this->addSql('ALTER TABLE post_image DROP FOREIGN KEY FK_522688B04B89032C');
        $this->addSql('ALTER TABLE post_image DROP FOREIGN KEY FK_522688B03DA5256D');
        $this->addSql('ALTER TABLE post_video DROP FOREIGN KEY FK_EBDC56C34B89032C');
        $this->addSql('ALTER TABLE post_video DROP FOREIGN KEY FK_EBDC56C329C1004E');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_post');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_artist');
        $this->addSql('DROP TABLE post_image');
        $this->addSql('DROP TABLE post_video');
        $this->addSql('DROP TABLE video');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
