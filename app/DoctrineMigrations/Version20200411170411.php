<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200411170411 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `bp_wording` (
            `id` int(11) NOT NULL,
            `mainService1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `mainService2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainService3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainService4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainService5` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainProvider1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainProvider2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainProvider3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainPartner` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainPartner2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainPartner3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainTarget1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `mainTarget2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainTarget3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainIntermediary1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainIntermediary2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainIntermediary3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `mainChannels` longtext COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\',
            `marketSize` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `marketStrategies` longtext COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\',
            `communicationChannels` longtext COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\',
            `hasMarketPlace` tinyint(1) NOT NULL,
            `addressMarketPlace_id` int(11) DEFAULT NULL,
            `businessPlan_id` int(11) DEFAULT NULL
               ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        $this->addSql('ALTER TABLE bp_wording ADD CONSTRAINT FK_C2A85C286153997C FOREIGN KEY (addressMarketPlace_id) REFERENCES bp_address (id)');

        $this->addSql('ALTER TABLE bp_wording ADD CONSTRAINT FK_C2A85C2885B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2A85C2885B7937E ON bp_wording (businessPlan_id)');

        $this->addSql('ALTER TABLE bp_address CHANGE street street VARCHAR(255) DEFAULT NULL, CHANGE code_postal code_postal VARCHAR(10) DEFAULT NULL, CHANGE city city VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE bp_wording CHANGE mainIntermediary1 mainIntermediary1 VARCHAR(255) DEFAULT NULL');

        $this->addSql('ALTER TABLE bp_service ADD nbWording INT NOT NULL');
        $this->addSql('ALTER TABLE promotion_catalog ADD hasWording TINYINT(1) NOT NULL');

        $this->addSql('INSERT INTO `page` (`id`, `name`, `step`, `info`) VALUES (NULL, \'Présentation détaillée\', 11, NULL)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE bp_wording');

        $this->addSql('ALTER TABLE bp_wording DROP FOREIGN KEY FK_C2A85C2885B7937E');
        $this->addSql('DROP INDEX UNIQ_C2A85C2885B7937E ON bp_wording');
        $this->addSql('ALTER TABLE bp_wording DROP businessPlan_id');

        $this->addSql('ALTER TABLE bp_address CHANGE street street VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE code_postal code_postal VARCHAR(10) NOT NULL COLLATE utf8_unicode_ci, CHANGE city city VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE bp_wording CHANGE mainIntermediary1 mainIntermediary1 VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');

        $this->addSql('ALTER TABLE bp_service DROP nbWording');
        $this->addSql('ALTER TABLE promotion_catalog DROP hasWording');
    }

}
