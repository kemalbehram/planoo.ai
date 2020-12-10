<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200325075650 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE bp_service set createdAt = \'1900-01-01 00:00:00\' where createdAt<\'1950-01-01 00:00:00\'');
        $this->addSql('UPDATE bp_service set updatedAt = \'1900-01-01 00:00:00\' where updatedAt<\'1950-01-01 00:00:00\'');


        $this->addSql('ALTER TABLE bp_service ADD businessPlan_id INT DEFAULT NULL');
        $this->addSql('UPDATE bp_service set businessPlan_id = id');
        $this->addSql('DELETE bp_service, bp_bp FROM bp_service left join bp_bp on bp_service.businessPlan_id=bp_bp.id where bp_bp.id is null');
        $this->addSql('ALTER TABLE bp_service ADD CONSTRAINT FK_36CCDB2885B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_36CCDB2885B7937E ON bp_service (businessPlan_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_service DROP FOREIGN KEY FK_36CCDB2885B7937E');
        $this->addSql('DROP INDEX UNIQ_36CCDB2885B7937E ON bp_service');
        $this->addSql('ALTER TABLE bp_service DROP businessPlan_id');
    }

}
