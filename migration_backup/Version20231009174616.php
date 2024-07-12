<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231009174616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'deleting building_dependencies table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE building_dependencies');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE building_dependencies (id INT AUTO_INCREMENT NOT NULL, building_id_id INT DEFAULT NULL, dependend_building_id_id INT DEFAULT NULL, dependend_building_level INT DEFAULT NULL, INDEX IDX_71F64E0213E42FCD (building_id_id), INDEX IDX_71F64E02FB378038 (dependend_building_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
    }
}
