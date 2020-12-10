<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201001170345 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE promotion_catalog ADD icon VARCHAR(255) NOT NULL');
        
        // Update BP Formulae
        $this->addSql('UPDATE `promotion_catalog` SET `icon` = \'icon-essentielle\' WHERE `promotion_catalog`.`id` = 29');
        $this->addSql('UPDATE `promotion_catalog` SET `icon` = \'icon-pro\' WHERE `promotion_catalog`.`id` = 30');
        $this->addSql('UPDATE `promotion_catalog` SET `icon` = \'icon-premium\' WHERE `promotion_catalog`.`id` = 31');
        
        // Update BP Upgrade
        $this->addSql('UPDATE `promotion_catalog` SET `icon` = \'icon-pro\' WHERE `promotion_catalog`.`id` = 43');
        $this->addSql('UPDATE `promotion_catalog` SET `icon` = \'icon-premium\' WHERE `promotion_catalog`.`id` = 44');
        $this->addSql('UPDATE `promotion_catalog` SET `icon` = \'icon-premium\' WHERE `promotion_catalog`.`id` = 45');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE promotion_catalog DROP icon');
    }
}
