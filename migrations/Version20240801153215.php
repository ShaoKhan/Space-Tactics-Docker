<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240801153215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'table to store users science';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_science (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, science_id INT DEFAULT NULL, science�level INT DEFAULT NULL, INDEX IDX_3F1A2FA29D86650F (user_id_id), INDEX IDX_3F1A2FA2F4A44BFA (science_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_science ADD CONSTRAINT FK_3F1A2FA29D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_science ADD CONSTRAINT FK_3F1A2FA2F4A44BFA FOREIGN KEY (science_id) REFERENCES sciences (id)');
    }

    public function down(Schema $schema): void
    {
       $this->addSql('ALTER TABLE user_science DROP FOREIGN KEY FK_3F1A2FA29D86650F');
        $this->addSql('ALTER TABLE user_science DROP FOREIGN KEY FK_3F1A2FA2F4A44BFA');
        $this->addSql('DROP TABLE user_science');
    }

    public function isTransactional(): bool
    {
        return false;
    }

}
