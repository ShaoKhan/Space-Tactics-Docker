<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715183926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create alliance table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alliance (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, alliance_ta VARCHAR(5) DEFAULT NULL, headline VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD alliance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64910A0EA3F FOREIGN KEY (alliance_id) REFERENCES alliance (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64910A0EA3F ON user (alliance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64910A0EA3F');
        $this->addSql('DROP TABLE alliance');
        $this->addSql('DROP INDEX IDX_8D93D64910A0EA3F ON user');
        $this->addSql('ALTER TABLE user DROP alliance_id');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
