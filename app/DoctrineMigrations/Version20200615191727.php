<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200615191727 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE partners_partner ADD idWordpressAffiliate INT DEFAULT NULL, CHANGE customDomain customDomain VARCHAR(128) DEFAULT NULL, CHANGE deletedAt deletedAt DATETIME DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42672866CD60BDBE ON partners_partner (idWordpressAffiliate)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_42672866CD60BDBE ON partners_partner');
        $this->addSql('ALTER TABLE partners_partner DROP idWordpressAffiliate, CHANGE customDomain customDomain VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, CHANGE deletedAt deletedAt DATETIME DEFAULT \'NULL\'');
    }
}
