<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200329123317 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE bp_cart ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE promotion_catalog ADD type VARCHAR(255) NOT NULL, DROP nb_printing');
        $this->addSql('UPDATE promotion_catalog SET type=\'Business Plan\' WHERE isFormule=1');
        $this->addSql('UPDATE promotion_catalog SET type=\'Service BP Interne\' WHERE isFormule=0');

        $this->addSql('INSERT INTO promotion_catalog (name, description, type, price,isFormule, isSmallBP, nb_day_expire_edit_date, nb_advice_hour, expert_review,custom_design, createdAt, updatedAt, deletedAt) VALUES
            (\'Design Personnalisé\',\'\',\'Service BP Interne\',     120,0,0,0,0,0,1,sysdate(),sysdate(),null),
            (\'Présentation projet\',\'\',\'Service Externe\',        120,0,0,0,0,0,0,sysdate(),sysdate(),null),
            (\'Avis d\\\'expert Pitch Deck\',\'\',\'Service Externe\',120,0,0,0,0,0,0,sysdate(),sysdate(),null),
            (\'Etude de marché\',\'\',\'Service Externe\',            120,0,0,0,0,0,0,sysdate(),sysdate(),null),
            (\'Commande BP complet\',\'\',\'Service Externe\',            0,0,0,0,0,0,0,sysdate(),sysdate(),null)
        ');

        $this->addSql('ALTER TABLE promotion_catalog DROP isFormule');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_cart DROP type');
        $this->addSql('ALTER TABLE promotion_catalog ADD isFormule TINYINT(1) NOT NULL, ADD nb_printing INT NOT NULL, ADD project_presentation INT NOT NULL, ADD expert_review_pitch_deck INT NOT NULL, ADD market_study INT NOT NULL, ADD hasTrialPeriod TINYINT(1) NOT NULL, ADD $isAutomaticalyOrdered TINYINT(1) NOT NULL, DROP type');
    }

}
