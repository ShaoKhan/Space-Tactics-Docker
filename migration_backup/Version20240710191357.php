<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240710191357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE buildings_queue (id INT AUTO_INCREMENT NOT NULL, planet_id INT NOT NULL, building_id INT NOT NULL, user_slug VARCHAR(255) NOT NULL, start_build DATETIME NOT NULL, end_build DATETIME NOT NULL, INDEX IDX_ED6E1AFCA25E9820 (planet_id), INDEX IDX_ED6E1AFC4D2A7E12 (building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE buildings_queue ADD CONSTRAINT FK_ED6E1AFCA25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
        $this->addSql('ALTER TABLE buildings_queue ADD CONSTRAINT FK_ED6E1AFC4D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id)');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE buildings ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE planet_building ADD planet_slug VARCHAR(255) NOT NULL, ADD building_slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD login_on DATETIME DEFAULT NULL, ADD logout_on DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, headers LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, queue_name VARCHAR(190) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E0FB7336F0 (queue_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE buildings_queue DROP FOREIGN KEY FK_ED6E1AFCA25E9820');
        $this->addSql('ALTER TABLE buildings_queue DROP FOREIGN KEY FK_ED6E1AFC4D2A7E12');
        $this->addSql('DROP TABLE buildings_queue');
        $this->addSql('ALTER TABLE buildings DROP slug');
        $this->addSql('ALTER TABLE planet_building DROP planet_slug, DROP building_slug');
        $this->addSql('ALTER TABLE user DROP login_on, DROP logout_on');
    }
}
