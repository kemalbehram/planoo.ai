<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200805085649 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_service DROP printing, DROP expert_review, DROP custom_design, DROP translation');
        $this->addSql('ALTER TABLE bp_information CHANGE activityDetail activityDetail VARCHAR(150) DEFAULT \' \' NOT NULL');
        $this->addSql('ALTER TABLE promotion_catalog DROP expert_review, DROP custom_design, DROP translation, DROP nbPrinting');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_information CHANGE activityDetail activityDetail VARCHAR(150) CHARACTER SET utf8 DEFAULT \'\' NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE bp_service ADD printing INT NOT NULL, ADD expert_review INT NOT NULL, ADD custom_design INT NOT NULL, ADD translation INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE promotion_catalog ADD expert_review INT DEFAULT 0 NOT NULL, ADD custom_design INT DEFAULT 0 NOT NULL, ADD translation INT DEFAULT 0 NOT NULL, ADD nbPrinting INT DEFAULT 0 NOT NULL');
    }
}
