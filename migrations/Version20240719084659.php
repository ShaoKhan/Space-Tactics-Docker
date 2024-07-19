<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719084659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'dependencies for science';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE science_dependencies (id INT AUTO_INCREMENT NOT NULL, science_id INT NOT NULL, required_science_id INT DEFAULT NULL, required_building_id INT DEFAULT NULL, required_science_level INT DEFAULT NULL, required_building_level INT DEFAULT NULL, INDEX IDX_C2391B81F4A44BFA (science_id), INDEX IDX_C2391B8168C3BD10 (required_science_id), INDEX IDX_C2391B81D691282 (required_building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE science_dependencies ADD CONSTRAINT FK_C2391B81F4A44BFA FOREIGN KEY (science_id) REFERENCES sciences (id)');
        $this->addSql('ALTER TABLE science_dependencies ADD CONSTRAINT FK_C2391B8168C3BD10 FOREIGN KEY (required_science_id) REFERENCES sciences (id)');
        $this->addSql('ALTER TABLE science_dependencies ADD CONSTRAINT FK_C2391B81D691282 FOREIGN KEY (required_building_id) REFERENCES buildings (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE science_dependencies DROP FOREIGN KEY FK_C2391B81F4A44BFA');
        $this->addSql('ALTER TABLE science_dependencies DROP FOREIGN KEY FK_C2391B8168C3BD10');
        $this->addSql('ALTER TABLE science_dependencies DROP FOREIGN KEY FK_C2391B81D691282');
        $this->addSql('DROP TABLE science_dependencies');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
