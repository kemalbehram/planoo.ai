<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200412141402 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('UPDATE promotion_catalog SET `hasWording` = \'1\' WHERE `promotion_catalog`.`id` = 30');
        $this->addSql('UPDATE promotion_catalog SET `hasWording` = \'1\' WHERE `promotion_catalog`.`id` = 31');

        $this->addSql('UPDATE `page` SET `name` = \'Mon entreprise\' WHERE `page`.`id` = 1');
        $this->addSql('UPDATE `page` SET `name` = \'Configuration BP\' WHERE `page`.`id` = 2');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
    }

}
