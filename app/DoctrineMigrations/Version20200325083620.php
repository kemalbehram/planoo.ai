<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200325083620 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_service ADD user_id INT DEFAULT NULL');
        $this->addSql('UPDATE bp_service join bp_bp on bp_service.businessPlan_id=bp_bp.id set bp_service.user_id = bp_bp.user_id');
        $this->addSql('ALTER TABLE bp_service ADD CONSTRAINT FK_36CCDB28A76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id)');
        $this->addSql('CREATE INDEX IDX_36CCDB28A76ED395 ON bp_service (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_service DROP FOREIGN KEY FK_36CCDB28A76ED395');
        $this->addSql('DROP INDEX IDX_36CCDB28A76ED395 ON bp_service');
        $this->addSql('ALTER TABLE bp_service DROP user_id');
    }

}
