<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922210648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'added messages and message_types tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message_types (id INT AUTO_INCREMENT NOT NULL, message_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, from_uuid VARCHAR(255) NOT NULL, from_name VARCHAR(255) NOT NULL, to_uuid VARCHAR(255) NOT NULL, to_name VARCHAR(255) NOT NULL, send_date DATETIME NOT NULL, message LONGTEXT DEFAULT NULL, was_read TINYINT(1) DEFAULT 0 NOT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, answered TINYINT(1) DEFAULT 0 NOT NULL, subject VARCHAR(255) NOT NULL, message_type INT NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE message_types');
        $this->addSql('DROP TABLE messages');
    }
}
