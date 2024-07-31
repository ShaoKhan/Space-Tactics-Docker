<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240731164428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change Fields to resolve Building and Dependency issues';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE planet_science ADD CONSTRAINT FK_F234662FA25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
        $this->addSql('ALTER TABLE planet_science ADD CONSTRAINT FK_F234662FF4A44BFA FOREIGN KEY (science_id) REFERENCES sciences (id)');
        $this->addSql('ALTER TABLE planet_science RENAME INDEX idx_20240715201320 TO IDX_F234662FA25E9820');
        $this->addSql('ALTER TABLE planet_science RENAME INDEX idx_20240715201431 TO IDX_F234662FF4A44BFA');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE planet_science DROP FOREIGN KEY FK_F234662FA25E9820');
        $this->addSql('ALTER TABLE planet_science DROP FOREIGN KEY FK_F234662FF4A44BFA');
        $this->addSql('ALTER TABLE planet_science RENAME INDEX idx_f234662fa25e9820 TO IDX_20240715201320');
        $this->addSql('ALTER TABLE planet_science RENAME INDEX idx_f234662ff4a44bfa TO IDX_20240715201431');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
