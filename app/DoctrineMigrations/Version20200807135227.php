<?php declare (strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200807135227 extends AbstractMigration {
    public function up(Schema $schema): void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        // ADD Catalog on BP
        $this->addSql('ALTER TABLE bp_bp ADD catalog_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bp_bp ADD CONSTRAINT FK_2EC16238CC3C66FC FOREIGN KEY (catalog_id) REFERENCES promotion_catalog (id)');
        $this->addSql('CREATE INDEX IDX_2EC16238CC3C66FC ON bp_bp (catalog_id)');

        // LINK BP.Catalog to Service.Catalog
        $this->addSql('UPDATE `bp_bp` SET `catalog_id` = (SELECT `bpCatalogKind_id` FROM `bp_service` WHERE `bp_service`.`id` = `bp_bp`.`service_id`)');

        // REMOVE Catalog on Service
        $this->addSql('ALTER TABLE bp_service DROP FOREIGN KEY FK_36CCDB28FC33D2FD');
        $this->addSql('DROP INDEX IDX_36CCDB28FC33D2FD ON bp_service');
        $this->addSql('ALTER TABLE bp_service DROP bpCatalogKind_id, DROP nbWording');
    }

    public function down(Schema $schema): void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        // ADD Catalog on Service
        $this->addSql('ALTER TABLE bp_service ADD bpCatalogKind_id INT DEFAULT NULL, ADD nbWording INT NOT NULL');
        $this->addSql('ALTER TABLE bp_service ADD CONSTRAINT FK_36CCDB28FC33D2FD FOREIGN KEY (bpCatalogKind_id) REFERENCES promotion_catalog (id)');
        $this->addSql('CREATE INDEX IDX_36CCDB28FC33D2FD ON bp_service (bpCatalogKind_id)');

        // LINK Service.Catalog to BP.Catalog
        $this->addSql('UPDATE `bp_service` SET `bpCatalogKind_id` = (SELECT `catalog_id` FROM `bp_bp` WHERE `bp_service`.`id` = `bp_bp`.`service_id`)');

        // REMOVE Catalog on BP
        $this->addSql('ALTER TABLE bp_bp DROP FOREIGN KEY FK_2EC16238CC3C66FC');
        $this->addSql('DROP INDEX IDX_2EC16238CC3C66FC ON bp_bp');
        $this->addSql('ALTER TABLE bp_bp DROP catalog_id');

    }
}
