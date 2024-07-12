<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240710210224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'change is_buildable default value to 1';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE buildings CHANGE is_buildable is_buildable TINYINT(1) DEFAULT 1 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE buildings CHANGE is_buildable is_buildable TINYINT(1) NOT NULL');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
