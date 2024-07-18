<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240718115808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'remove false or useless fields from tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alliance DROP FOREIGN KEY FK_6CBA583F6359A4C6');
        $this->addSql('DROP INDEX IDX_6CBA583F6359A4C6 ON alliance');
        $this->addSql('ALTER TABLE alliance DROP alliance_member_id');
        $this->addSql('ALTER TABLE alliance_member ADD alliance_slug VARCHAR(255) DEFAULT NULL, ADD user_slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496359A4C6');
        $this->addSql('DROP INDEX IDX_8D93D6496359A4C6 ON user');
        $this->addSql('ALTER TABLE user DROP alliance_member_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alliance ADD alliance_member_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE alliance ADD CONSTRAINT FK_6CBA583F6359A4C6 FOREIGN KEY (alliance_member_id) REFERENCES alliance_member (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6CBA583F6359A4C6 ON alliance (alliance_member_id)');
        $this->addSql('ALTER TABLE alliance_member DROP alliance_slug, DROP user_slug');
        $this->addSql('ALTER TABLE user ADD alliance_member_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496359A4C6 FOREIGN KEY (alliance_member_id) REFERENCES alliance_member (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D6496359A4C6 ON user (alliance_member_id)');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
