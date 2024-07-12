<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231011194914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planet_building CHANGE planet_id planet_id INT NOT NULL');
        $this->addSql('ALTER TABLE planet_building ADD CONSTRAINT FK_92265400A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
        $this->addSql('CREATE INDEX IDX_92265400A25E9820 ON planet_building (planet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planet_building DROP FOREIGN KEY FK_92265400A25E9820');
        $this->addSql('DROP INDEX IDX_92265400A25E9820 ON planet_building');
        $this->addSql('ALTER TABLE planet_building CHANGE planet_id planet_id VARCHAR(255) NOT NULL');
    }
}
