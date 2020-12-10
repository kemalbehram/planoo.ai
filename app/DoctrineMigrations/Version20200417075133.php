<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200417075133 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        $this->addSql('ALTER TABLE bp_service CHANGE advice_hour advice_hour DOUBLE PRECISION NOT NULL');

        $this->addSql('ALTER TABLE promotion_catalog ADD buyable TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE promotion_catalog ADD translation INT DEFAULT 0 NOT NULL');
        $this->addSql('UPDATE promotion_catalog set createdAt = \'1900-01-01 00:00:00\' where createdAt<\'1950-01-01 00:00:00\'');
        $this->addSql('UPDATE promotion_catalog set updatedAt = \'1900-01-01 00:00:00\' where updatedAt<\'1950-01-01 00:00:00\'');
        $this->addSql('ALTER TABLE promotion_catalog CHANGE nb_advice_hour nb_advice_hour DOUBLE PRECISION NOT NULL');

        $this->addSql('UPDATE `promotion_catalog` SET `name` = \'Basic\', `description` = \'Business plan Basic\', `nb_day_expire_edit_date` = \'30\' WHERE `promotion_catalog`.`id` = 29');
        $this->addSql('UPDATE `promotion_catalog` SET `name` = \'Pro\', `description` = \'Business Plan Pro\', `expert_review` = \'0\' WHERE `promotion_catalog`.`id` = 30');
        $this->addSql('UPDATE `promotion_catalog` SET `name` = \'Confort\', `description` = \'Business Plan Confort\', `price` = \'499\', `nb_advice_hour` = \'0.25\' WHERE `promotion_catalog`.`id` = 31');
        $this->addSql('INSERT INTO `promotion_catalog` (`id`, `name`, `description`, `price`, `isSmallBP`, `nb_day_expire_edit_date`, `nb_advice_hour`, `expert_review`, `createdAt`, `updatedAt`, `deletedAt`, `custom_design`, `type`, `hasWording`) VALUES (NULL, \'Par activité\', \'Business Plan par Activité\', \'499\', \'0\', \'365\', \'0.25\', \'1\', sysdate(), sysdate(), NULL, \'0\', \'Business Plan\', \'1\')');
        $this->addSql('INSERT INTO `promotion_catalog` (`id`, `name`, `description`, `price`, `isSmallBP`, `nb_day_expire_edit_date`, `nb_advice_hour`, `expert_review`, `createdAt`, `updatedAt`, `deletedAt`, `custom_design`, `type`, `hasWording`) VALUES (NULL, \'Par objectif\', \'Business Plan par Objectif\', \'499\', \'0\', \'365\', \'0.25\', \'1\', sysdate(), sysdate(), NULL, \'0\', \'Business Plan\', \'1\')');
        $this->addSql('UPDATE `promotion_catalog` SET `buyable` = \'0\' WHERE `promotion_catalog`.`id` = 32');

        $this->addSql('UPDATE `promotion_catalog` SET `name` = \'Relecture Pitch Deck\', `price` = \'199\' WHERE `promotion_catalog`.`id` = 36');
        $this->addSql('UPDATE `promotion_catalog` SET `name` = \'Etude locale de marché\', `description` = \'Etude locale de marché\', `price` = \'499\' WHERE `promotion_catalog`.`id` = 37');

        $this->addSql('UPDATE `promotion_catalog` SET `description` = \'Rend votre Business plan éditable 30 jours supplémentaires\' WHERE `promotion_catalog`.`id` = 33');
        $this->addSql('UPDATE `promotion_catalog` SET `description` = \'Nos experts relisent votre document et vous font part de leur remarques\', `price` = \'119\' WHERE `promotion_catalog`.`id` = 25');
        $this->addSql('UPDATE `promotion_catalog` SET `name` = \'Design\', `description` = \'Nos graphistes mettent en valeur votre entreprise et son activité\', `price` = \'49\' WHERE `promotion_catalog`.`id` = 34');
        $this->addSql('UPDATE `promotion_catalog` SET `description` = \'Echangez avec nos experts et bénéficiez de leurs conseils quel que soit votre objectif\', `price` = \'149\' WHERE `promotion_catalog`.`id` = 26');
        $this->addSql('INSERT INTO `promotion_catalog` (`id`, `name`, `description`, `price`, `isSmallBP`, `nb_day_expire_edit_date`, `nb_advice_hour`, `expert_review`, `createdAt`, `updatedAt`, `deletedAt`, `custom_design`, `type`, `hasWording`, `buyable`) VALUES (NULL, \'15 min de conseil\', \'Echangez avec nos experts et bénéficiez de leurs conseils quel que soit votre objectif\', \'99999\', \'0\', \'0\', \'0.25\', \'0\', sysdate(), sysdate(), NULL, \'0\', \'Service BP Interne\', \'0\', \'0\')');
        $this->addSql('INSERT INTO `promotion_catalog` (`id`, `name`, `description`, `price`, `isSmallBP`, `nb_day_expire_edit_date`, `nb_advice_hour`, `expert_review`, `createdAt`, `updatedAt`, `deletedAt`, `custom_design`, `type`, `hasWording`, `buyable`, `translation`) VALUES (NULL, \'Traduction\', \'Besoin de votre business plan dans une autre langue ? Nos traducteurs s’en occupent pour vous\', \'99\', \'0\', \'0\', \'0\', \'0\', SYSDATE(), SYSDATE(), NULL, \'0\', \'Service BP Interne\', \'0\', \'1\', \'1\')');
        $this->addSql('UPDATE `promotion_catalog` SET `name` = \'Impression\', `description` = \'Votre business plan relié\' WHERE `promotion_catalog`.`id` = 27');

        $this->addSql('ALTER TABLE bp_service ADD translation INT DEFAULT 0 NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
    }

}
