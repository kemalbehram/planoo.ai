<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180714124616 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE partners_partner ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL, ADD deletedAt DATETIME DEFAULT NULL');
        $this->addSql('UPDATE partners_partner SET createdAt=DATE(\'2018-05-01\'),updatedAt=DATE(\'2018-05-01\')');
        $this->addSql('UPDATE user_user SET partner_id=1 WHERE partner_id is null');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE partners_partner DROP deletedAt, DROP createdAt, DROP updatedAt');
    }

}
