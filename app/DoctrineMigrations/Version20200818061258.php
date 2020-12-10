<?php declare (strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200818061258 extends AbstractMigration {
    public function up(Schema $schema): void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        // 0) CLEAN (only up) Remove Saisonnalite broken record (bp does not exist anymore)
        $this->addSql('DELETE `bp_saisonnalite`  FROM `bp_saisonnalite` LEFT JOIN `bp_bp` ON `bp_saisonnalite`.`businessPlan_Id` = `bp_bp`.`id` WHERE `bp_bp`.`id` IS NULL AND `bp_saisonnalite`.`businessPlan_Id` IS NOT NULL;');

        //  1) ADD strong link from payment to cart
        $this->addSql('ALTER TABLE payment ADD cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D1AD5CDBF FOREIGN KEY (cart_id) REFERENCES bp_cart (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D28840D1AD5CDBF ON payment (cart_id)');

        //  2) SET strong link data between payment and cart
        $this->addSql('UPDATE `payment` SET `cart_id` = (SELECT `id` FROM `bp_cart` WHERE `bp_cart`.`payment_id` = `payment`.`id` LIMIT 1)');

        //  3) REMOVE weak link from payment to cart
        $this->addSql('ALTER TABLE payment DROP cartId');

        // 4) CLEAN (only up) Payment without cart
        $this->addSql('DELETE FROM `payment` WHERE `payment`.`cart_id` IS NULL');

    }

    public function down(Schema $schema): void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //  3) ADD weak link from payment to cart
        $this->addSql('ALTER TABLE payment ADD cartId VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');

        //  2) SET weak link data between payment and cart
        $this->addSql('UPDATE `payment` SET `payment`.`cartId` = `payment`.`cart_id`');

        //  1) REMOVE strong link from payment to cart
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D1AD5CDBF');
        $this->addSql('DROP INDEX UNIQ_6D28840D1AD5CDBF ON payment');
        $this->addSql('ALTER TABLE payment DROP cart_id');
    }
}
