<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200417122246 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_wording DROP INDEX FK_C2A85C286153997C, ADD UNIQUE INDEX UNIQ_C2A85C286153997C (addressMarketPlace_id)');
        $this->addSql('ALTER TABLE bp_wording CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE promotion_catalog ADD nbPrinting INT DEFAULT 0 NOT NULL, CHANGE isSmallBP isSmallBP TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE nb_day_expire_edit_date nb_day_expire_edit_date INT DEFAULT 0 NOT NULL, CHANGE nb_advice_hour nb_advice_hour DOUBLE PRECISION DEFAULT \'0\' NOT NULL, CHANGE expert_review expert_review INT DEFAULT 0 NOT NULL, CHANGE custom_design custom_design INT DEFAULT 0 NOT NULL, CHANGE hasWording hasWording TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('UPDATE `promotion_catalog` SET `nbPrinting` = \'1\' WHERE `promotion_catalog`.`id` = 27');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_wording DROP INDEX UNIQ_C2A85C286153997C, ADD INDEX FK_C2A85C286153997C (addressMarketPlace_id)');
        $this->addSql('ALTER TABLE bp_wording MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE bp_wording DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE bp_wording CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE promotion_catalog DROP nbPrinting, CHANGE isSmallBP isSmallBP TINYINT(1) NOT NULL, CHANGE hasWording hasWording TINYINT(1) NOT NULL, CHANGE nb_day_expire_edit_date nb_day_expire_edit_date INT NOT NULL, CHANGE nb_advice_hour nb_advice_hour DOUBLE PRECISION NOT NULL, CHANGE expert_review expert_review INT NOT NULL, CHANGE custom_design custom_design INT NOT NULL');
    }

}
