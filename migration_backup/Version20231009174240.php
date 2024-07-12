<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231009174240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE buddylist (id INT AUTO_INCREMENT NOT NULL, src INT NOT NULL, target INT NOT NULL, accepted TINYINT(1) NOT NULL, asked DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE buildings (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, building_class INT NOT NULL, one_per_planet TINYINT(1) NOT NULL, factor DOUBLE PRECISION DEFAULT NULL, level_max INT NOT NULL, cost_metal INT NOT NULL, cost_crystal INT NOT NULL, cost_deuterium INT NOT NULL, cost_dark_matter INT NOT NULL, cost_energy INT NOT NULL, storage_metal DOUBLE PRECISION DEFAULT NULL, storage_crystal DOUBLE PRECISION DEFAULT NULL, storage_deuterium DOUBLE PRECISION DEFAULT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE building_dependencies (id INT AUTO_INCREMENT NOT NULL, building_id_id INT DEFAULT NULL, dependend_building_id_id INT DEFAULT NULL, dependend_building_level INT DEFAULT NULL, INDEX IDX_71F64E0213E42FCD (building_id_id), INDEX IDX_71F64E02FB378038 (dependend_building_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, from_uuid VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, from_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, to_uuid VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, to_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, send_date DATETIME NOT NULL, message LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, was_read TINYINT(1) DEFAULT 0 NOT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, answered TINYINT(1) DEFAULT 0 NOT NULL, subject VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, message_type INT NOT NULL, slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE message_types (id INT AUTO_INCREMENT NOT NULL, message_type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, headers LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, queue_name VARCHAR(190) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), INDEX IDX_75EA56E0FB7336F0 (queue_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE planet (id INT AUTO_INCREMENT NOT NULL, user_uuid VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, universe INT NOT NULL, system_x INT NOT NULL, system_y INT NOT NULL, system_z INT NOT NULL, type INT NOT NULL, destroyed SMALLINT DEFAULT NULL, metal DOUBLE PRECISION DEFAULT NULL, metal_max DOUBLE PRECISION DEFAULT NULL, crystal DOUBLE PRECISION DEFAULT NULL, crystal_max DOUBLE PRECISION DEFAULT NULL, deuterium DOUBLE PRECISION DEFAULT NULL, deuterium_max DOUBLE PRECISION DEFAULT NULL, metal_building INT DEFAULT NULL, crystal_building INT DEFAULT NULL, deuterium_building INT DEFAULT NULL, solar_building INT DEFAULT NULL, nuclear_building INT DEFAULT NULL, robot_building INT DEFAULT NULL, nanite_building INT DEFAULT NULL, hangar_building INT DEFAULT NULL, metalstorage_building INT DEFAULT NULL, crystalstorage_building INT DEFAULT NULL, deuteriumstorage_building INT DEFAULT NULL, laboratory_building INT DEFAULT NULL, university_building INT DEFAULT NULL, alliancehangar_building INT DEFAULT NULL, terraforming_building INT NOT NULL, moonbase_building INT NOT NULL, jumpgate_building INT NOT NULL, laserphalanx_building INT NOT NULL, missilesilo_building INT DEFAULT NULL, missilelauncher_defense INT DEFAULT NULL, phalanx_defense INT DEFAULT NULL, smalllaser_defense INT DEFAULT NULL, biglaser_defense INT DEFAULT NULL, gausscannon_defense INT DEFAULT NULL, ioncannon_defense INT DEFAULT NULL, plasmacannon_defense INT DEFAULT NULL, smallshield_defense INT DEFAULT NULL, bigshield_defense INT DEFAULT NULL, planetshield_defense INT DEFAULT NULL, gravitoncannon_defense INT DEFAULT NULL, interceptormissile_defense INT DEFAULT NULL, orbitaldefenseplatform_defense INT DEFAULT NULL, smalltransportship_ship INT DEFAULT NULL, bigtransportship_ship INT DEFAULT NULL, lighthunter_ship INT DEFAULT NULL, heavyhunter_ship INT DEFAULT NULL, cruiser_ship INT DEFAULT NULL, battleship_ship INT DEFAULT NULL, colonyship_ship INT DEFAULT NULL, smallrecycler_ship INT DEFAULT NULL, mediumrecycler_ship INT DEFAULT NULL, bigrecycler_ship INT DEFAULT NULL, spyprobe_ship INT DEFAULT NULL, bomber_ship INT DEFAULT NULL, solarsatellite_ship INT DEFAULT NULL, destroyer_ship INT DEFAULT NULL, deathstar_ship INT DEFAULT NULL, battlecruiser_ship INT DEFAULT NULL, lunenoire_ship INT DEFAULT NULL, evolutiontransporter_ship INT DEFAULT NULL, gigarecycler_ship INT DEFAULT NULL, dmcollector_ship INT DEFAULT NULL, darkmatter DOUBLE PRECISION DEFAULT NULL, slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_update DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_68136AA5989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE planet_building (id INT AUTO_INCREMENT NOT NULL, planet_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, building_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, building_level INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE planet_type (id INT AUTO_INCREMENT NOT NULL, type INT NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, fields INT NOT NULL, fields_max INT NOT NULL, temp_min DOUBLE PRECISION NOT NULL, temp_max DOUBLE PRECISION NOT NULL, diameter INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE sciences (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, science_class INT NOT NULL, one_per_planet INT NOT NULL, factor INT NOT NULL, level_max INT NOT NULL, cost_metal INT NOT NULL, cost_crystal INT NOT NULL, cost_deuterium INT NOT NULL, cost_dark_matter INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, game_name VARCHAR(125) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, timezone VARCHAR(75) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, msg_delete_after INT NOT NULL, user_delete_after INT NOT NULL, inactive_delete_after INT NOT NULL, reminder_mail SMALLINT DEFAULT NULL, activate_emails SMALLINT DEFAULT NULL, sender_type VARCHAR(75) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, sender_mail VARCHAR(125) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, sendmailpath VARCHAR(125) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, smtp_host VARCHAR(125) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, smtp_ssl_tls VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, smtp_port INT DEFAULT NULL, smtp_username VARCHAR(125) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, smtp_password VARCHAR(125) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, send_reminder_after INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE ships (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, class INT NOT NULL, one_per_planet INT NOT NULL, level_max INT NOT NULL, cost_metal INT NOT NULL, cost_crystal INT NOT NULL, cost_deuterium INT NOT NULL, cost_dark_matter INT NOT NULL, slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE support (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, datum DATETIME NOT NULL, subject VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, theme VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, message LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, processed_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, processed_since DATETIME DEFAULT NULL, answered TINYINT(1) DEFAULT NULL, closed TINYINT(1) DEFAULT NULL, parent_message INT DEFAULT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE uni (id INT AUTO_INCREMENT NOT NULL, users_amount INT DEFAULT NULL, game_speed DOUBLE PRECISION NOT NULL, fleet_speed DOUBLE PRECISION NOT NULL, resource_multiplier INT NOT NULL, storage_multiplier INT NOT NULL, message_delete_behavior SMALLINT DEFAULT NULL, message_delete_days SMALLINT DEFAULT NULL, halt_speed SMALLINT DEFAULT NULL, fleet_cdr SMALLINT NOT NULL, def_in_tf SMALLINT NOT NULL, planet_fields INT NOT NULL, uni_name VARCHAR(75) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, uni_enabled SMALLINT NOT NULL, closed_text LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, metal_standard_income INT NOT NULL, crystal_standard_income INT NOT NULL, deuterium_standard_income INT NOT NULL, energy_standard_income INT NOT NULL, noob_protection SMALLINT DEFAULT NULL, noob_protectiontime INT DEFAULT NULL, forum_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, admin_attackable SMALLINT DEFAULT NULL, language VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, teamspeak_mod SMALLINT DEFAULT NULL, teamspeak_server VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, teamspeak_tcp_port SMALLINT DEFAULT NULL, teamspeak_udp_port SMALLINT DEFAULT NULL, registration_closed SMALLINT DEFAULT NULL, welcome_text VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, min_build_time SMALLINT DEFAULT NULL, modules VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, tradeable_ships VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, tradeable_ships_fee INT DEFAULT NULL, galaxy_width INT NOT NULL, galaxy_height INT NOT NULL, galaxy_depth INT NOT NULL, max_construction_count SMALLINT NOT NULL, max_science_count SMALLINT NOT NULL, max_ship_count SMALLINT NOT NULL, max_start_planets_per_player SMALLINT NOT NULL, max_planets_astrophysics SMALLINT NOT NULL, max_planets_officers SMALLINT NOT NULL, max_planets_science SMALLINT NOT NULL, flight_deuterium_cost_per_click INT NOT NULL, max_dm_missions SMALLINT NOT NULL, max_resource_overflow INT NOT NULL, moon_chance_factor INT NOT NULL, moon_chance INT NOT NULL, trader_dm_cost INT NOT NULL, university_factor_science INT NOT NULL, max_fleets_per_association INT NOT NULL, delete_moon_debris SMALLINT DEFAULT NULL, min_umode_time SMALLINT NOT NULL, gate_interval_time INT NOT NULL, start_metal INT NOT NULL, start_crystal INT NOT NULL, start_deuterium INT NOT NULL, start_dm INT NOT NULL, referal_active SMALLINT NOT NULL, referal_bonus_dm INT NOT NULL, referal_minpoints INT NOT NULL, referal_max_referals INT NOT NULL, del_user_manual SMALLINT NOT NULL, send_inavtive_mail SMALLINT NOT NULL, silo_size_factor INT NOT NULL, energy_factor INT NOT NULL, alliance_min_points INT NOT NULL, expedition_res_limit INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, username VARCHAR(75) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(125) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, locale VARCHAR(5) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, uni SMALLINT NOT NULL, register_on DATETIME NOT NULL, activate_on DATETIME DEFAULT NULL, referal_on DATETIME DEFAULT NULL, referal_by INT DEFAULT NULL, last_active DATETIME DEFAULT NULL, roles JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', is_verified TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE buddylist');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE buildings');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE building_dependencies');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE messages');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE message_types');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE messenger_messages');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE planet');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE planet_building');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE planet_type');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE sciences');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE server');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE ships');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE support');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE uni');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1043Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1043Platform'."
        );

        $this->addSql('DROP TABLE user');
    }
}
