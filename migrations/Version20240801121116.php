<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240801121116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'table for ship build dependencies';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE ship_dependencies (id INT AUTO_INCREMENT NOT NULL, ship_id INT DEFAULT NULL, required_building_id INT DEFAULT NULL, required_science_id INT DEFAULT NULL, required_building_level INT DEFAULT NULL, required_science_level INT DEFAULT NULL, INDEX IDX_D590C3EDC256317D (ship_id), INDEX IDX_D590C3EDD691282 (required_building_id), INDEX IDX_D590C3ED68C3BD10 (required_science_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ship_dependencies ADD CONSTRAINT FK_D590C3EDC256317D FOREIGN KEY (ship_id) REFERENCES ships (id)');
        $this->addSql('ALTER TABLE ship_dependencies ADD CONSTRAINT FK_D590C3EDD691282 FOREIGN KEY (required_building_id) REFERENCES buildings (id)');
        $this->addSql('ALTER TABLE ship_dependencies ADD CONSTRAINT FK_D590C3ED68C3BD10 FOREIGN KEY (required_science_id) REFERENCES sciences (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ship_dependencies DROP FOREIGN KEY FK_D590C3EDC256317D');
        $this->addSql('ALTER TABLE ship_dependencies DROP FOREIGN KEY FK_D590C3EDD691282');
        $this->addSql('ALTER TABLE ship_dependencies DROP FOREIGN KEY FK_D590C3ED68C3BD10');
        $this->addSql('DROP TABLE ship_dependencies');
    }

    public function isTransactional(): bool
    {
        return FALSE;
    }
}
