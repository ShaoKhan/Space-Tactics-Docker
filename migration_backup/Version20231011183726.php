<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231011183726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE building_dependencies (id INT AUTO_INCREMENT NOT NULL, building_id INT NOT NULL, required_building_id INT DEFAULT NULL, required_science_id INT DEFAULT NULL, required_building_level INT DEFAULT NULL, required_science_level INT DEFAULT NULL, INDEX IDX_71F64E024D2A7E12 (building_id), INDEX IDX_71F64E02D691282 (required_building_id), INDEX IDX_71F64E0268C3BD10 (required_science_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE building_dependencies ADD CONSTRAINT FK_71F64E024D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id)');
        $this->addSql('ALTER TABLE building_dependencies ADD CONSTRAINT FK_71F64E02D691282 FOREIGN KEY (required_building_id) REFERENCES buildings (id)');
        $this->addSql('ALTER TABLE building_dependencies ADD CONSTRAINT FK_71F64E0268C3BD10 FOREIGN KEY (required_science_id) REFERENCES sciences (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE building_dependencies DROP FOREIGN KEY FK_71F64E024D2A7E12');
        $this->addSql('ALTER TABLE building_dependencies DROP FOREIGN KEY FK_71F64E02D691282');
        $this->addSql('ALTER TABLE building_dependencies DROP FOREIGN KEY FK_71F64E0268C3BD10');
        $this->addSql('DROP TABLE building_dependencies');
    }
}
