<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241214112131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alliance (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, alliance_tag VARCHAR(5) DEFAULT NULL, headline VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, logo_name VARCHAR(255) DEFAULT NULL, logo_size INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alliance_member (id INT AUTO_INCREMENT NOT NULL, alliance_slug VARCHAR(255) DEFAULT NULL, user_slug VARCHAR(255) DEFAULT NULL, joined_on DATETIME DEFAULT NULL, ranking VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE buddylist (id INT AUTO_INCREMENT NOT NULL, src INT NOT NULL, target INT NOT NULL, accepted TINYINT(1) NOT NULL, asked DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE building_dependencies (id INT AUTO_INCREMENT NOT NULL, building_id INT NOT NULL, required_building_id INT DEFAULT NULL, required_science_id INT DEFAULT NULL, required_building_level INT DEFAULT NULL, required_science_level INT DEFAULT NULL, INDEX IDX_71F64E024D2A7E12 (building_id), INDEX IDX_71F64E02D691282 (required_building_id), INDEX IDX_71F64E0268C3BD10 (required_science_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE buildings (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, factor DOUBLE PRECISION DEFAULT NULL, level_max INT NOT NULL, cost_metal INT NOT NULL, cost_crystal INT NOT NULL, cost_deuterium INT NOT NULL, cost_dark_matter INT NOT NULL, cost_energy INT NOT NULL, storage_metal DOUBLE PRECISION DEFAULT NULL, storage_crystal DOUBLE PRECISION DEFAULT NULL, storage_deuterium DOUBLE PRECISION DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, building_class INT NOT NULL, one_per_planet TINYINT(1) NOT NULL, is_buildable TINYINT(1) DEFAULT 1 NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE buildings_queue (id INT AUTO_INCREMENT NOT NULL, planet_slug VARCHAR(255) NOT NULL, building_slug VARCHAR(255) NOT NULL, user_slug VARCHAR(255) NOT NULL, start_build DATETIME NOT NULL, end_build DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_types (id INT AUTO_INCREMENT NOT NULL, message_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, from_uuid VARCHAR(255) NOT NULL, from_name VARCHAR(255) NOT NULL, to_uuid VARCHAR(255) NOT NULL, to_name VARCHAR(255) NOT NULL, send_date DATETIME NOT NULL, message LONGTEXT DEFAULT NULL, was_read TINYINT(1) DEFAULT 0 NOT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, answered TINYINT(1) DEFAULT 0 NOT NULL, subject VARCHAR(255) NOT NULL, message_type INT NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planet (id INT AUTO_INCREMENT NOT NULL, user_uuid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, universe INT NOT NULL, system_x INT NOT NULL, system_y INT NOT NULL, system_z INT NOT NULL, type INT NOT NULL, destroyed SMALLINT DEFAULT NULL, metal DOUBLE PRECISION DEFAULT NULL, metal_max DOUBLE PRECISION DEFAULT NULL, crystal DOUBLE PRECISION DEFAULT NULL, crystal_max DOUBLE PRECISION DEFAULT NULL, deuterium DOUBLE PRECISION DEFAULT NULL, deuterium_max DOUBLE PRECISION DEFAULT NULL, metal_building INT DEFAULT NULL, crystal_building INT DEFAULT NULL, deuterium_building INT DEFAULT NULL, solar_building INT DEFAULT NULL, nuclear_building INT DEFAULT NULL, robot_building INT DEFAULT NULL, nanite_building INT DEFAULT NULL, hangar_building INT DEFAULT NULL, metalstorage_building INT DEFAULT NULL, crystalstorage_building INT DEFAULT NULL, deuteriumstorage_building INT DEFAULT NULL, laboratory_building INT DEFAULT NULL, university_building INT DEFAULT NULL, alliancehangar_building INT DEFAULT NULL, missilesilo_building INT DEFAULT NULL, missilelauncher_defense INT DEFAULT NULL, phalanx_defense INT DEFAULT NULL, smalllaser_defense INT DEFAULT NULL, biglaser_defense INT DEFAULT NULL, gausscannon_defense INT DEFAULT NULL, ioncannon_defense INT DEFAULT NULL, plasmacannon_defense INT DEFAULT NULL, smallshield_defense INT DEFAULT NULL, bigshield_defense INT DEFAULT NULL, planetshield_defense INT DEFAULT NULL, gravitoncannon_defense INT DEFAULT NULL, interceptormissile_defense INT DEFAULT NULL, orbitaldefenseplatform_defense INT DEFAULT NULL, smalltransportship_ship INT DEFAULT NULL, bigtransportship_ship INT DEFAULT NULL, lighthunter_ship INT DEFAULT NULL, heavyhunter_ship INT DEFAULT NULL, cruiser_ship INT DEFAULT NULL, battleship_ship INT DEFAULT NULL, colonyship_ship INT DEFAULT NULL, smallrecycler_ship INT DEFAULT NULL, mediumrecycler_ship INT DEFAULT NULL, bigrecycler_ship INT DEFAULT NULL, spyprobe_ship INT DEFAULT NULL, bomber_ship INT DEFAULT NULL, solarsatellite_ship INT DEFAULT NULL, destroyer_ship INT DEFAULT NULL, deathstar_ship INT DEFAULT NULL, battlecruiser_ship INT DEFAULT NULL, lunenoire_ship INT DEFAULT NULL, evolutiontransporter_ship INT DEFAULT NULL, gigarecycler_ship INT DEFAULT NULL, dmcollector_ship INT DEFAULT NULL, darkmatter DOUBLE PRECISION DEFAULT NULL, slug VARCHAR(255) NOT NULL, terraforming_building INT DEFAULT NULL, moonbase_building INT DEFAULT NULL, jumpgate_building INT DEFAULT NULL, laserphalanx_building INT DEFAULT NULL, last_update DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_68136AA5989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planet_building (id INT AUTO_INCREMENT NOT NULL, planet_id INT NOT NULL, building_id INT NOT NULL, building_level INT NOT NULL, planet_slug VARCHAR(255) NOT NULL, building_slug VARCHAR(255) NOT NULL, INDEX IDX_92265400A25E9820 (planet_id), INDEX IDX_922654004D2A7E12 (building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planet_science (id INT AUTO_INCREMENT NOT NULL, planet_id INT NOT NULL, science_id INT NOT NULL, planet_slug VARCHAR(255) NOT NULL, science_slug VARCHAR(255) NOT NULL, science_level INT NOT NULL, INDEX IDX_F234662FA25E9820 (planet_id), INDEX IDX_F234662FF4A44BFA (science_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planet_type (id INT AUTO_INCREMENT NOT NULL, type INT NOT NULL, image VARCHAR(255) DEFAULT NULL, fields INT NOT NULL, fields_max INT NOT NULL, temp_min DOUBLE PRECISION NOT NULL, temp_max DOUBLE PRECISION NOT NULL, diameter INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE science_dependencies (id INT AUTO_INCREMENT NOT NULL, science_id INT NOT NULL, required_science_id INT DEFAULT NULL, required_building_id INT DEFAULT NULL, required_science_level INT DEFAULT NULL, required_building_level INT DEFAULT NULL, INDEX IDX_C2391B81F4A44BFA (science_id), INDEX IDX_C2391B8168C3BD10 (required_science_id), INDEX IDX_C2391B81D691282 (required_building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sciences (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, science_class INT NOT NULL, one_per_planet INT NOT NULL, factor INT NOT NULL, level_max INT NOT NULL, cost_metal INT NOT NULL, cost_crystal INT NOT NULL, cost_deuterium INT NOT NULL, cost_dark_matter INT NOT NULL, image VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, game_name VARCHAR(125) NOT NULL, timezone VARCHAR(75) DEFAULT NULL, msg_delete_after INT NOT NULL, user_delete_after INT NOT NULL, inactive_delete_after INT NOT NULL, reminder_mail SMALLINT DEFAULT NULL, activate_emails SMALLINT DEFAULT NULL, sender_type VARCHAR(75) DEFAULT NULL, sender_mail VARCHAR(125) DEFAULT NULL, sendmailpath VARCHAR(125) DEFAULT NULL, smtp_host VARCHAR(125) DEFAULT NULL, smtp_ssl_tls VARCHAR(50) DEFAULT NULL, smtp_port INT DEFAULT NULL, smtp_username VARCHAR(125) DEFAULT NULL, smtp_password VARCHAR(125) DEFAULT NULL, send_reminder_after INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ship_dependencies (id INT AUTO_INCREMENT NOT NULL, ship_id INT NOT NULL, required_building_id INT DEFAULT NULL, required_science_id INT DEFAULT NULL, required_building_level INT DEFAULT NULL, required_science_level INT DEFAULT NULL, INDEX IDX_D590C3EDC256317D (ship_id), INDEX IDX_D590C3EDD691282 (required_building_id), INDEX IDX_D590C3ED68C3BD10 (required_science_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ships (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, class INT NOT NULL, one_per_planet INT NOT NULL, level_max INT NOT NULL, cost_metal INT NOT NULL, cost_crystal INT NOT NULL, cost_deuterium INT NOT NULL, cost_dark_matter INT NOT NULL, slug VARCHAR(255) NOT NULL, consumption_deuterium INT NOT NULL, capacity INT NOT NULL, attack INT NOT NULL, defense INT NOT NULL, drive_science INT NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE support (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, datum DATETIME NOT NULL, subject VARCHAR(255) NOT NULL, theme VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, processed_by VARCHAR(255) DEFAULT NULL, processed_since DATETIME DEFAULT NULL, answered TINYINT(1) DEFAULT NULL, closed TINYINT(1) DEFAULT NULL, parent_message INT DEFAULT NULL, username VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uni (id INT AUTO_INCREMENT NOT NULL, users_amount INT DEFAULT NULL, game_speed DOUBLE PRECISION NOT NULL, fleet_speed DOUBLE PRECISION NOT NULL, resource_multiplier INT NOT NULL, storage_multiplier INT NOT NULL, message_delete_behavior SMALLINT DEFAULT NULL, message_delete_days SMALLINT DEFAULT NULL, halt_speed SMALLINT DEFAULT NULL, fleet_cdr SMALLINT NOT NULL, def_in_tf SMALLINT NOT NULL, planet_fields INT NOT NULL, uni_name VARCHAR(75) NOT NULL, uni_enabled SMALLINT NOT NULL, closed_text LONGTEXT DEFAULT NULL, metal_standard_income INT NOT NULL, crystal_standard_income INT NOT NULL, deuterium_standard_income INT NOT NULL, energy_standard_income INT NOT NULL, noob_protection SMALLINT DEFAULT NULL, noob_protectiontime INT DEFAULT NULL, forum_url VARCHAR(255) DEFAULT NULL, admin_attackable SMALLINT DEFAULT NULL, language VARCHAR(100) DEFAULT NULL, teamspeak_mod SMALLINT DEFAULT NULL, teamspeak_server VARCHAR(255) DEFAULT NULL, teamspeak_tcp_port SMALLINT DEFAULT NULL, teamspeak_udp_port SMALLINT DEFAULT NULL, registration_closed SMALLINT DEFAULT NULL, welcome_text VARCHAR(255) DEFAULT NULL, min_build_time SMALLINT DEFAULT NULL, modules VARCHAR(255) DEFAULT NULL, tradeable_ships VARCHAR(255) DEFAULT NULL, tradeable_ships_fee INT DEFAULT NULL, galaxy_width INT NOT NULL, galaxy_height INT NOT NULL, galaxy_depth INT NOT NULL, max_construction_count SMALLINT NOT NULL, max_science_count SMALLINT NOT NULL, max_ship_count SMALLINT NOT NULL, max_start_planets_per_player SMALLINT NOT NULL, max_planets_astrophysics SMALLINT NOT NULL, max_planets_officers SMALLINT NOT NULL, max_planets_science SMALLINT NOT NULL, flight_deuterium_cost_per_click INT NOT NULL, max_dm_missions SMALLINT NOT NULL, max_resource_overflow INT NOT NULL, moon_chance_factor INT NOT NULL, moon_chance INT NOT NULL, trader_dm_cost INT NOT NULL, university_factor_science INT NOT NULL, max_fleets_per_association INT NOT NULL, delete_moon_debris SMALLINT DEFAULT NULL, min_umode_time SMALLINT NOT NULL, gate_interval_time INT NOT NULL, start_metal INT NOT NULL, start_crystal INT NOT NULL, start_deuterium INT NOT NULL, start_dm INT NOT NULL, referal_active SMALLINT NOT NULL, referal_bonus_dm INT NOT NULL, referal_minpoints INT NOT NULL, referal_max_referals INT NOT NULL, del_user_manual SMALLINT NOT NULL, send_inavtive_mail SMALLINT NOT NULL, silo_size_factor INT NOT NULL, energy_factor INT NOT NULL, alliance_min_points INT NOT NULL, expedition_res_limit INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, username VARCHAR(75) NOT NULL, email VARCHAR(125) NOT NULL, password VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, uni SMALLINT NOT NULL, register_on DATETIME NOT NULL, activate_on DATETIME DEFAULT NULL, referal_on DATETIME DEFAULT NULL, referal_by INT DEFAULT NULL, last_active DATETIME DEFAULT NULL, roles JSON DEFAULT NULL, is_verified TINYINT(1) NOT NULL, login_on DATETIME DEFAULT NULL, logout_on DATETIME DEFAULT NULL, alliance LONGTEXT DEFAULT NULL, vacation SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_science (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, science_id INT DEFAULT NULL, science_level INT DEFAULT NULL, INDEX IDX_3F1A2FA29D86650F (user_id_id), INDEX IDX_3F1A2FA2F4A44BFA (science_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE building_dependencies ADD CONSTRAINT FK_71F64E024D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id)');
        $this->addSql('ALTER TABLE building_dependencies ADD CONSTRAINT FK_71F64E02D691282 FOREIGN KEY (required_building_id) REFERENCES buildings (id)');
        $this->addSql('ALTER TABLE building_dependencies ADD CONSTRAINT FK_71F64E0268C3BD10 FOREIGN KEY (required_science_id) REFERENCES sciences (id)');
        $this->addSql('ALTER TABLE planet_building ADD CONSTRAINT FK_92265400A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
        $this->addSql('ALTER TABLE planet_building ADD CONSTRAINT FK_922654004D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id)');
        $this->addSql('ALTER TABLE planet_science ADD CONSTRAINT FK_F234662FA25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
        $this->addSql('ALTER TABLE planet_science ADD CONSTRAINT FK_F234662FF4A44BFA FOREIGN KEY (science_id) REFERENCES sciences (id)');
        $this->addSql('ALTER TABLE science_dependencies ADD CONSTRAINT FK_C2391B81F4A44BFA FOREIGN KEY (science_id) REFERENCES sciences (id)');
        $this->addSql('ALTER TABLE science_dependencies ADD CONSTRAINT FK_C2391B8168C3BD10 FOREIGN KEY (required_science_id) REFERENCES sciences (id)');
        $this->addSql('ALTER TABLE science_dependencies ADD CONSTRAINT FK_C2391B81D691282 FOREIGN KEY (required_building_id) REFERENCES buildings (id)');
        $this->addSql('ALTER TABLE ship_dependencies ADD CONSTRAINT FK_D590C3EDC256317D FOREIGN KEY (ship_id) REFERENCES ships (id)');
        $this->addSql('ALTER TABLE ship_dependencies ADD CONSTRAINT FK_D590C3EDD691282 FOREIGN KEY (required_building_id) REFERENCES buildings (id)');
        $this->addSql('ALTER TABLE ship_dependencies ADD CONSTRAINT FK_D590C3ED68C3BD10 FOREIGN KEY (required_science_id) REFERENCES sciences (id)');
        $this->addSql('ALTER TABLE user_science ADD CONSTRAINT FK_3F1A2FA29D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_science ADD CONSTRAINT FK_3F1A2FA2F4A44BFA FOREIGN KEY (science_id) REFERENCES sciences (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE building_dependencies DROP FOREIGN KEY FK_71F64E024D2A7E12');
        $this->addSql('ALTER TABLE building_dependencies DROP FOREIGN KEY FK_71F64E02D691282');
        $this->addSql('ALTER TABLE building_dependencies DROP FOREIGN KEY FK_71F64E0268C3BD10');
        $this->addSql('ALTER TABLE planet_building DROP FOREIGN KEY FK_92265400A25E9820');
        $this->addSql('ALTER TABLE planet_building DROP FOREIGN KEY FK_922654004D2A7E12');
        $this->addSql('ALTER TABLE planet_science DROP FOREIGN KEY FK_F234662FA25E9820');
        $this->addSql('ALTER TABLE planet_science DROP FOREIGN KEY FK_F234662FF4A44BFA');
        $this->addSql('ALTER TABLE science_dependencies DROP FOREIGN KEY FK_C2391B81F4A44BFA');
        $this->addSql('ALTER TABLE science_dependencies DROP FOREIGN KEY FK_C2391B8168C3BD10');
        $this->addSql('ALTER TABLE science_dependencies DROP FOREIGN KEY FK_C2391B81D691282');
        $this->addSql('ALTER TABLE ship_dependencies DROP FOREIGN KEY FK_D590C3EDC256317D');
        $this->addSql('ALTER TABLE ship_dependencies DROP FOREIGN KEY FK_D590C3EDD691282');
        $this->addSql('ALTER TABLE ship_dependencies DROP FOREIGN KEY FK_D590C3ED68C3BD10');
        $this->addSql('ALTER TABLE user_science DROP FOREIGN KEY FK_3F1A2FA29D86650F');
        $this->addSql('ALTER TABLE user_science DROP FOREIGN KEY FK_3F1A2FA2F4A44BFA');
        $this->addSql('DROP TABLE alliance');
        $this->addSql('DROP TABLE alliance_member');
        $this->addSql('DROP TABLE buddylist');
        $this->addSql('DROP TABLE building_dependencies');
        $this->addSql('DROP TABLE buildings');
        $this->addSql('DROP TABLE buildings_queue');
        $this->addSql('DROP TABLE message_types');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE planet');
        $this->addSql('DROP TABLE planet_building');
        $this->addSql('DROP TABLE planet_science');
        $this->addSql('DROP TABLE planet_type');
        $this->addSql('DROP TABLE science_dependencies');
        $this->addSql('DROP TABLE sciences');
        $this->addSql('DROP TABLE server');
        $this->addSql('DROP TABLE ship_dependencies');
        $this->addSql('DROP TABLE ships');
        $this->addSql('DROP TABLE support');
        $this->addSql('DROP TABLE uni');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_science');
    }
}
