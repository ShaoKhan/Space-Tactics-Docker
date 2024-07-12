<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240710193907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'make fields default null';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planet CHANGE terraforming_building terraforming_building INT DEFAULT NULL, CHANGE moonbase_building moonbase_building INT DEFAULT NULL, CHANGE laserphalanx_building laserphalanx_building INT DEFAULT NULL, CHANGE jumpgate_building jumpgate_building INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planet CHANGE terraforming_building terraforming_building INT NOT NULL, CHANGE moonbase_building moonbase_building INT NOT NULL, CHANGE jumpgate_building jumpgate_building INT NOT NULL, CHANGE laserphalanx_building laserphalanx_building INT NOT NULL');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
