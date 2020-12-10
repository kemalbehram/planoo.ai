<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180428090210 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_service CHANGE expert_advice printing INT NOT NULL');
        $this->addSql('ALTER TABLE promotion_catalog CHANGE nb_expert_advice nb_printing INT NOT NULL');
        $this->addSql('ALTER TABLE lexik_trans_unit_translations ADD modified_manually TINYINT(1) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_service CHANGE printing expert_advice INT NOT NULL');
        $this->addSql('ALTER TABLE lexik_trans_unit_translations DROP modified_manually');
        $this->addSql('ALTER TABLE promotion_catalog CHANGE nb_printing nb_expert_advice INT NOT NULL');
    }
}
