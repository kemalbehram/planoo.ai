<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200812133457 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        // 1) CLEAN (only up) Remove Cart and CartCatalog Broken record (bp does not exist anymore)
        $this->addSql('DELETE `join_cart_catalog`,`bp_cart` FROM `join_cart_catalog` LEFT JOIN `bp_cart`  ON `join_cart_catalog`.`cart_id` = `bp_cart`.`id` LEFT JOIN `bp_bp` ON `bp_cart`.`businessPlan_Id` = `bp_bp`.`id` WHERE `bp_bp`.`id` IS NULL AND `bp_cart`.`businessPlan_Id` IS NOT NULL;');
        
        // 2) CLEAN (only up) Remove Cart (with no CartCatalog associated) Broken record (bp does not exist anymore)
        $this->addSql('DELETE `bp_cart`  FROM `bp_cart` LEFT JOIN `bp_bp` ON `bp_cart`.`businessPlan_Id` = `bp_bp`.`id` WHERE `bp_bp`.`id` IS NULL AND `bp_cart`.`businessPlan_Id` IS NOT NULL;');


        // 3) ADD CartCatalog.BusinessPlan
        $this->addSql('ALTER TABLE join_cart_catalog ADD businessPlan_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE join_cart_catalog ADD CONSTRAINT FK_F95EC51985B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('CREATE INDEX IDX_F95EC51985B7937E ON join_cart_catalog (businessPlan_id)');

        // 4) LINK CartCatalog.BusinessPlan to Cart.BusinessPlan
        $this->addSql('UPDATE `join_cart_catalog` SET `businessPlan_id` = (SELECT `businessPlan_id` FROM `bp_cart` WHERE `bp_cart`.`id` = `join_cart_catalog`.`cart_id`)');

        //  5) REMOVE Cart.BusinessPlan
        $this->addSql('ALTER TABLE bp_cart DROP FOREIGN KEY FK_1AC380C285B7937E');
        $this->addSql('DROP INDEX IDX_1AC380C285B7937E ON bp_cart');
        $this->addSql('ALTER TABLE bp_cart DROP businessPlan_id');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //  5) ADD Cart.BusinessPlan
        $this->addSql('ALTER TABLE bp_cart ADD businessPlan_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bp_cart ADD CONSTRAINT FK_1AC380C285B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('CREATE INDEX IDX_1AC380C285B7937E ON bp_cart (businessPlan_id)');

        // 4) REVERT LINK CartCatalog.BusinessPlan to Cart.BusinessPlan
        $this->addSql('UPDATE `bp_cart` SET `businessPlan_id` = (SELECT `businessPlan_id` FROM `join_cart_catalog` WHERE `join_cart_catalog`.`cart_id` = `bp_cart`.`id` LIMIT 1)');

        // 3) REMOVE CartCatalog.BusinessPlan
        $this->addSql('ALTER TABLE join_cart_catalog DROP FOREIGN KEY FK_F95EC51985B7937E');
        $this->addSql('DROP INDEX IDX_F95EC51985B7937E ON join_cart_catalog');
        $this->addSql('ALTER TABLE join_cart_catalog DROP businessPlan_id');
    }
}
