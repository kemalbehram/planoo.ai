<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200407063733 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE joorney_order ADD cart_id INT DEFAULT NULL, DROP cart');
        $this->addSql('ALTER TABLE joorney_order ADD CONSTRAINT FK_EB07816E1AD5CDBF FOREIGN KEY (cart_id) REFERENCES bp_cart (id)');
        $this->addSql('CREATE INDEX IDX_EB07816E1AD5CDBF ON joorney_order (cart_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE joorney_order DROP FOREIGN KEY FK_EB07816E1AD5CDBF');
        $this->addSql('DROP INDEX IDX_EB07816E1AD5CDBF ON joorney_order');
        $this->addSql('ALTER TABLE joorney_order ADD cart LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:object)\', DROP cart_id');
    }
}
