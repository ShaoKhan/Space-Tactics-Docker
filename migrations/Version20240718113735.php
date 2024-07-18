<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240718113735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'new table to store members of alliance';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alliance_member (id INT AUTO_INCREMENT NOT NULL, joined_on DATETIME DEFAULT NULL, ranking VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alliance ADD alliance_member_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE alliance ADD CONSTRAINT FK_6CBA583F6359A4C6 FOREIGN KEY (alliance_member_id) REFERENCES alliance_member (id)');
        $this->addSql('CREATE INDEX IDX_6CBA583F6359A4C6 ON alliance (alliance_member_id)');
        $this->addSql('ALTER TABLE user ADD alliance_member_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496359A4C6 FOREIGN KEY (alliance_member_id) REFERENCES alliance_member (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6496359A4C6 ON user (alliance_member_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alliance DROP FOREIGN KEY FK_6CBA583F6359A4C6');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496359A4C6');
        $this->addSql('DROP TABLE alliance_member');
        $this->addSql('DROP INDEX IDX_6CBA583F6359A4C6 ON alliance');
        $this->addSql('ALTER TABLE alliance DROP alliance_member_id');
        $this->addSql('DROP INDEX IDX_8D93D6496359A4C6 ON user');
        $this->addSql('ALTER TABLE user DROP alliance_member_id');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
