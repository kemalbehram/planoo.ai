<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180605052447 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_address ADD deletedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE bp_bfr ADD deletedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
        $this->addSql('DROP INDEX UNIQ_2EC16238989D9B62 ON bp_bp');
        $this->addSql('ALTER TABLE bp_bp DROP name, CHANGE slug hash VARCHAR(128) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2EC16238D1B862B8 ON bp_bp (hash)');
        $this->addSql('ALTER TABLE bp_charge ADD deletedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE bp_exercice ADD deletedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE bp_funding ADD deletedAt DATETIME DEFAULT NULL, ADD fundingCreatedAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE bp_income ADD deletedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE bp_info_charge ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL, ADD deletedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE bp_info_product ADD deletedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE bp_information ADD deletedAt DATETIME DEFAULT NULL, ADD informationCreatedAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE bp_investissement ADD deletedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE bp_produit ADD deletedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE bp_saisonnalite ADD deletedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE bp_service ADD deletedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE bp_staff ADD deletedAt DATETIME DEFAULT NULL, ADD staffCreatedAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_address DROP deletedAt, DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE bp_bfr DROP deletedAt, DROP createdAt, DROP updatedAt');
        $this->addSql('DROP INDEX UNIQ_2EC16238D1B862B8 ON bp_bp');
        $this->addSql('ALTER TABLE bp_bp ADD name VARCHAR(150) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE hash slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2EC16238989D9B62 ON bp_bp (slug)');
        $this->addSql('ALTER TABLE bp_charge DROP deletedAt, DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE bp_exercice DROP deletedAt, DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE bp_funding DROP deletedAt, DROP fundingCreatedAt, DROP updatedAt');
        $this->addSql('ALTER TABLE bp_income DROP deletedAt, DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE bp_info_charge DROP createdAt, DROP updatedAt, DROP deletedAt');
        $this->addSql('ALTER TABLE bp_info_product DROP deletedAt, DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE bp_information DROP deletedAt, DROP informationCreatedAt, DROP updatedAt');
        $this->addSql('ALTER TABLE bp_investissement DROP deletedAt, DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE bp_produit DROP deletedAt, DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE bp_saisonnalite DROP deletedAt, DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE bp_service DROP deletedAt, DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE bp_staff DROP deletedAt, DROP staffCreatedAt, DROP updatedAt');
    }
}
