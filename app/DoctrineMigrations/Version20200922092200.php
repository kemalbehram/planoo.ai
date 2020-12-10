<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200922092200 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('INSERT INTO `page` (`id`, `name`, `step`, `info`) VALUES (NULL, \'Etude de marché sectorielle\', 12, NULL)');

        $this->addSql('CREATE TABLE bp_wording_marketstudy (id INT AUTO_INCREMENT NOT NULL, mainMarket VARCHAR(255) NOT NULL, businessPlan_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_EACC2F1285B7937E (businessPlan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bp_wording_marketstudy ADD CONSTRAINT FK_EACC2F1285B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('ALTER TABLE bp_wording_customwriting DROP FOREIGN KEY FK_C2A85C286153997C');
        $this->addSql('ALTER TABLE bp_wording_customwriting DROP FOREIGN KEY FK_C2A85C2885B7937E');
        $this->addSql('DROP INDEX uniq_c2a85c2885b7937e ON bp_wording_customwriting');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6564310185B7937E ON bp_wording_customwriting (businessPlan_id)');
        $this->addSql('DROP INDEX uniq_c2a85c286153997c ON bp_wording_customwriting');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_656431016153997C ON bp_wording_customwriting (addressMarketPlace_id)');
        $this->addSql('ALTER TABLE bp_wording_customwriting ADD CONSTRAINT FK_C2A85C286153997C FOREIGN KEY (addressMarketPlace_id) REFERENCES bp_address (id)');
        $this->addSql('ALTER TABLE bp_wording_customwriting ADD CONSTRAINT FK_C2A85C2885B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE bp_wording_marketstudy');
        $this->addSql('ALTER TABLE bp_wording_customwriting DROP FOREIGN KEY FK_6564310185B7937E');
        $this->addSql('ALTER TABLE bp_wording_customwriting DROP FOREIGN KEY FK_656431016153997C');
        $this->addSql('DROP INDEX uniq_656431016153997c ON bp_wording_customwriting');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2A85C286153997C ON bp_wording_customwriting (addressMarketPlace_id)');
        $this->addSql('DROP INDEX uniq_6564310185b7937e ON bp_wording_customwriting');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2A85C2885B7937E ON bp_wording_customwriting (businessPlan_id)');
        $this->addSql('ALTER TABLE bp_wording_customwriting ADD CONSTRAINT FK_6564310185B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('ALTER TABLE bp_wording_customwriting ADD CONSTRAINT FK_656431016153997C FOREIGN KEY (addressMarketPlace_id) REFERENCES bp_address (id)');
    }
}
