<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240801192807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add image field for ships';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ships ADD image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ships DROP image');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
