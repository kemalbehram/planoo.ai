<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200326203940 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE joorney_order (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, payment_id INT DEFAULT NULL, catalog_id INT DEFAULT NULL, statut VARCHAR(255) NOT NULL, cart LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', businessPlan_id INT DEFAULT NULL, INDEX IDX_EB07816EA76ED395 (user_id), UNIQUE INDEX UNIQ_EB07816E4C3A3BB (payment_id), INDEX IDX_EB07816E85B7937E (businessPlan_id), INDEX IDX_EB07816ECC3C66FC (catalog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE joorney_order ADD CONSTRAINT FK_EB07816EA76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id)');
        $this->addSql('ALTER TABLE joorney_order ADD CONSTRAINT FK_EB07816E4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE joorney_order ADD CONSTRAINT FK_EB07816E85B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('ALTER TABLE joorney_order ADD CONSTRAINT FK_EB07816ECC3C66FC FOREIGN KEY (catalog_id) REFERENCES promotion_catalog (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE joorney_order');
    }

}
