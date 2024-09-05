<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240805200455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'changing field dependencies';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE buildings_queue DROP FOREIGN KEY FK_ED6E1AFCA25E9820');
        $this->addSql('ALTER TABLE buildings_queue DROP FOREIGN KEY FK_ED6E1AFC4D2A7E12');
        $this->addSql('DROP INDEX IDX_ED6E1AFCA25E9820 ON buildings_queue');
        $this->addSql('DROP INDEX IDX_ED6E1AFC4D2A7E12 ON buildings_queue');
        $this->addSql('ALTER TABLE buildings_queue ADD planet VARCHAR(255) NOT NULL, ADD building VARCHAR(255) NOT NULL, DROP planet_id, DROP building_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE buildings_queue ADD planet_id INT NOT NULL, ADD building_id INT NOT NULL, DROP planet, DROP building');
        $this->addSql('ALTER TABLE buildings_queue ADD CONSTRAINT FK_ED6E1AFCA25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE buildings_queue ADD CONSTRAINT FK_ED6E1AFC4D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_ED6E1AFCA25E9820 ON buildings_queue (planet_id)');
        $this->addSql('CREATE INDEX IDX_ED6E1AFC4D2A7E12 ON buildings_queue (building_id)');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
