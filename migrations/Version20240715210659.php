<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715210659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'try to add a user as leader to alliance';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE alliance DROP INDEX UNIQ_6CBA583F73154ED4, ADD INDEX IDX_6CBA583F73154ED4 (leader_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64910A0EA3F');
        $this->addSql('DROP INDEX IDX_8D93D64910A0EA3F ON user');
        $this->addSql('ALTER TABLE user DROP alliance_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alliance DROP INDEX IDX_6CBA583F73154ED4, ADD UNIQUE INDEX UNIQ_6CBA583F73154ED4 (leader_id)');
        $this->addSql('ALTER TABLE user ADD alliance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64910A0EA3F FOREIGN KEY (alliance_id) REFERENCES alliance (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D64910A0EA3F ON user (alliance_id)');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
