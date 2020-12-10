<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200407115541 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE bp_information set updatedAt = \'1900-01-01 00:00:00\' where updatedAt<\'1950-01-01 00:00:00\'');
        $this->addSql('UPDATE bp_information set informationCreatedAt = \'1900-01-01 00:00:00\' where informationCreatedAt<\'1950-01-01 00:00:00\'');
        $this->addSql('ALTER TABLE bp_information ADD activity_id INT DEFAULT NULL, DROP activity, CHANGE job job VARCHAR(150) DEFAULT NULL');
        $this->addSql('ALTER TABLE bp_information ADD CONSTRAINT FK_AED22D581C06096 FOREIGN KEY (activity_id) REFERENCES bp_activity (id)');
        $this->addSql('CREATE INDEX IDX_AED22D581C06096 ON bp_information (activity_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_information DROP FOREIGN KEY FK_AED22D581C06096');
        $this->addSql('DROP INDEX IDX_AED22D581C06096 ON bp_information');
        $this->addSql('ALTER TABLE bp_information ADD activity VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP activity_id, CHANGE job job VARCHAR(150) NOT NULL COLLATE utf8_unicode_ci');
    }

}
