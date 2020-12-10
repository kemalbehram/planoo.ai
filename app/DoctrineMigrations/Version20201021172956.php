<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201021172956 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_wording_marketstudy ADD marketSize VARCHAR(255) NOT NULL, ADD hasMarketPlace TINYINT(1) NOT NULL, ADD transportDuration TIME DEFAULT NULL, ADD transportMode VARCHAR(255) NOT NULL, ADD addressMarketPlace_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bp_wording_marketstudy ADD CONSTRAINT FK_EACC2F126153997C FOREIGN KEY (addressMarketPlace_id) REFERENCES bp_address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EACC2F126153997C ON bp_wording_marketstudy (addressMarketPlace_id)');
        $this->addSql('ALTER TABLE bp_wording_customwriting DROP FOREIGN KEY FK_C2A85C286153997C');
        $this->addSql('DROP INDEX UNIQ_656431016153997C ON bp_wording_customwriting');
        $this->addSql('ALTER TABLE bp_wording_customwriting DROP marketSize, DROP hasMarketPlace, DROP addressMarketPlace_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_wording_customwriting ADD marketSize VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, ADD hasMarketPlace TINYINT(1) NOT NULL, ADD addressMarketPlace_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bp_wording_customwriting ADD CONSTRAINT FK_C2A85C286153997C FOREIGN KEY (addressMarketPlace_id) REFERENCES bp_address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_656431016153997C ON bp_wording_customwriting (addressMarketPlace_id)');
        $this->addSql('ALTER TABLE bp_wording_marketstudy DROP FOREIGN KEY FK_EACC2F126153997C');
        $this->addSql('DROP INDEX UNIQ_EACC2F126153997C ON bp_wording_marketstudy');
        $this->addSql('ALTER TABLE bp_wording_marketstudy DROP marketSize, DROP hasMarketPlace, DROP transportDuration, DROP transportMode, DROP addressMarketPlace_id');
    }
}
