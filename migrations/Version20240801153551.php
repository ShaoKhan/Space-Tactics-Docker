<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240801153551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'fix column name bug';
    }

    public function up(Schema $schema): void
    {
         $this->addSql('ALTER TABLE user_science CHANGE science�level science_level INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
         $this->addSql('ALTER TABLE user_science CHANGE science_level science�level INT DEFAULT NULL');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
