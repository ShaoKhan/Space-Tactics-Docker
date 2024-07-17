<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716061446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'alliance is now a textfield in user table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64910A0EA3F');
        $this->addSql('DROP INDEX IDX_8D93D64910A0EA3F ON user');
        $this->addSql('ALTER TABLE user ADD alliance LONGTEXT DEFAULT NULL, DROP alliance_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD alliance_id INT DEFAULT NULL, DROP alliance');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64910A0EA3F FOREIGN KEY (alliance_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D64910A0EA3F ON user (alliance_id)');
    }
}
