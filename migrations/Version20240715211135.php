<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715211135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'remove leader from alliance';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alliance DROP FOREIGN KEY FK_6CBA583F73154ED4');
        $this->addSql('DROP INDEX IDX_6CBA583F73154ED4 ON alliance');
        $this->addSql('ALTER TABLE alliance DROP leader_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alliance ADD leader_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE alliance ADD CONSTRAINT FK_6CBA583F73154ED4 FOREIGN KEY (leader_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6CBA583F73154ED4 ON alliance (leader_id)');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
