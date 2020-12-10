<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201126224033 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_bp DROP FOREIGN KEY FK_2EC16238A76ED395');
        $this->addSql('ALTER TABLE bp_bp ADD CONSTRAINT FK_2EC16238A76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bp_service DROP FOREIGN KEY FK_36CCDB2885B7937E');
        $this->addSql('ALTER TABLE bp_service ADD CONSTRAINT FK_36CCDB2885B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bp_bp DROP FOREIGN KEY FK_2EC16238ED5CA9E6');
        $this->addSql('ALTER TABLE bp_bp ADD CONSTRAINT FK_2EC16238ED5CA9E6 FOREIGN KEY (service_id) REFERENCES bp_service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bp_bfr DROP FOREIGN KEY FK_58BD33B685B7937E');
        $this->addSql('ALTER TABLE bp_bfr ADD CONSTRAINT FK_58BD33B685B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bp_saisonnalite DROP FOREIGN KEY FK_DB3F488B85B7937E');
        $this->addSql('ALTER TABLE bp_saisonnalite ADD CONSTRAINT FK_DB3F488B85B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bp_wording_customwriting DROP FOREIGN KEY FK_C2A85C2885B7937E');
        $this->addSql('ALTER TABLE bp_wording_customwriting ADD CONSTRAINT FK_6564310185B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bp_wording_marketstudy DROP FOREIGN KEY FK_EACC2F1285B7937E');
        $this->addSql('ALTER TABLE bp_wording_marketstudy ADD CONSTRAINT FK_EACC2F1285B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D1AD5CDBF');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D1AD5CDBF FOREIGN KEY (cart_id) REFERENCES bp_cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bp_cart DROP FOREIGN KEY FK_1AC380C24C3A3BB');
        $this->addSql('ALTER TABLE bp_cart DROP FOREIGN KEY FK_1AC380C2A76ED395');
        $this->addSql('ALTER TABLE bp_cart ADD CONSTRAINT FK_1AC380C24C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bp_cart ADD CONSTRAINT FK_1AC380C2A76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE bp_investissement DROP FOREIGN KEY FK_21C950485B7937E');
        $this->addSql('ALTER TABLE bp_investissement ADD CONSTRAINT FK_21C950485B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE bp_charge DROP FOREIGN KEY FK_9F8B0D585B7937E');
        $this->addSql('ALTER TABLE bp_charge ADD CONSTRAINT FK_9F8B0D585B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bp_info_charge DROP FOREIGN KEY FK_BB22C13455284914');
        $this->addSql('ALTER TABLE bp_info_charge ADD CONSTRAINT FK_BB22C13455284914 FOREIGN KEY (charge_id) REFERENCES bp_charge (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE bp_information DROP FOREIGN KEY FK_AED22D585B7937E');
        $this->addSql('ALTER TABLE bp_information ADD CONSTRAINT FK_AED22D585B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE bp_info_product DROP FOREIGN KEY FK_D3A44DC8F347EFB');
        $this->addSql('ALTER TABLE bp_info_product ADD CONSTRAINT FK_D3A44DC8F347EFB FOREIGN KEY (produit_id) REFERENCES bp_produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bp_produit DROP FOREIGN KEY FK_FEF4ADDD85B7937E');
        $this->addSql('ALTER TABLE bp_produit ADD CONSTRAINT FK_FEF4ADDD85B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bp_exercice DROP FOREIGN KEY FK_B9A78D0E85B7937E');
        $this->addSql('ALTER TABLE bp_exercice ADD CONSTRAINT FK_B9A78D0E85B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE bp_info_product DROP FOREIGN KEY FK_D3A44DC889D40298');
        $this->addSql('ALTER TABLE bp_info_product ADD CONSTRAINT FK_D3A44DC889D40298 FOREIGN KEY (exercice_id) REFERENCES bp_exercice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bp_info_charge DROP FOREIGN KEY FK_BB22C13489D40298');
        $this->addSql('ALTER TABLE bp_info_charge ADD CONSTRAINT FK_BB22C13489D40298 FOREIGN KEY (exercice_id) REFERENCES bp_exercice (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE bp_address DROP FOREIGN KEY FK_DA1F2E7BA76ED395');
        $this->addSql('ALTER TABLE bp_address ADD CONSTRAINT FK_DA1F2E7BA76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id) ON DELETE CASCADE');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_bp DROP FOREIGN KEY FK_2EC16238A76ED395');
        $this->addSql('ALTER TABLE bp_bp ADD CONSTRAINT FK_2EC16238A76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id)');
        $this->addSql('ALTER TABLE bp_service DROP FOREIGN KEY FK_36CCDB2885B7937E');
        $this->addSql('ALTER TABLE bp_service ADD CONSTRAINT FK_36CCDB2885B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('ALTER TABLE bp_bp DROP FOREIGN KEY FK_2EC16238ED5CA9E6');
        $this->addSql('ALTER TABLE bp_bp ADD CONSTRAINT FK_2EC16238ED5CA9E6 FOREIGN KEY (service_id) REFERENCES bp_service (id)');
        $this->addSql('ALTER TABLE bp_bfr DROP FOREIGN KEY FK_58BD33B685B7937E');
        $this->addSql('ALTER TABLE bp_bfr ADD CONSTRAINT FK_58BD33B685B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('ALTER TABLE bp_saisonnalite DROP FOREIGN KEY FK_DB3F488B85B7937E');
        $this->addSql('ALTER TABLE bp_saisonnalite ADD CONSTRAINT FK_DB3F488B85B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('ALTER TABLE bp_wording_customwriting DROP FOREIGN KEY FK_6564310185B7937E');
        $this->addSql('ALTER TABLE bp_wording_customwriting ADD CONSTRAINT FK_C2A85C2885B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('ALTER TABLE bp_wording_marketstudy DROP FOREIGN KEY FK_EACC2F1285B7937E');
        $this->addSql('ALTER TABLE bp_wording_marketstudy ADD CONSTRAINT FK_EACC2F1285B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');

        $this->addSql('ALTER TABLE bp_cart DROP FOREIGN KEY FK_1AC380C2A76ED395');
        $this->addSql('ALTER TABLE bp_cart DROP FOREIGN KEY FK_1AC380C24C3A3BB');
        $this->addSql('ALTER TABLE bp_cart ADD CONSTRAINT FK_1AC380C2A76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id)');
        $this->addSql('ALTER TABLE bp_cart ADD CONSTRAINT FK_1AC380C24C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D1AD5CDBF');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D1AD5CDBF FOREIGN KEY (cart_id) REFERENCES bp_cart (id)');

        $this->addSql('ALTER TABLE bp_investissement DROP FOREIGN KEY FK_21C950485B7937E');
        $this->addSql('ALTER TABLE bp_investissement ADD CONSTRAINT FK_21C950485B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('ALTER TABLE bp_staff DROP FOREIGN KEY FK_6210162985B7937E');
        $this->addSql('ALTER TABLE bp_staff ADD CONSTRAINT FK_6210162985B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bp_funding DROP FOREIGN KEY FK_45C902C85B7937E');
        $this->addSql('ALTER TABLE bp_funding ADD CONSTRAINT FK_45C902C85B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bp_funding DROP FOREIGN KEY FK_45C902C85B7937E');
        $this->addSql('ALTER TABLE bp_funding ADD CONSTRAINT FK_45C902C85B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('ALTER TABLE bp_staff DROP FOREIGN KEY FK_6210162985B7937E');
        $this->addSql('ALTER TABLE bp_staff ADD CONSTRAINT FK_6210162985B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');

        $this->addSql('ALTER TABLE bp_charge DROP FOREIGN KEY FK_9F8B0D585B7937E');
        $this->addSql('ALTER TABLE bp_charge ADD CONSTRAINT FK_9F8B0D585B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('ALTER TABLE bp_info_charge DROP FOREIGN KEY FK_BB22C13455284914');
        $this->addSql('ALTER TABLE bp_info_charge ADD CONSTRAINT FK_BB22C13455284914 FOREIGN KEY (charge_id) REFERENCES bp_charge (id)');

        $this->addSql('ALTER TABLE bp_information DROP FOREIGN KEY FK_AED22D585B7937E');
        $this->addSql('ALTER TABLE bp_information ADD CONSTRAINT FK_AED22D585B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');

        $this->addSql('ALTER TABLE bp_exercice DROP FOREIGN KEY FK_B9A78D0E85B7937E');
        $this->addSql('ALTER TABLE bp_exercice ADD CONSTRAINT FK_B9A78D0E85B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');
        $this->addSql('ALTER TABLE bp_info_product DROP FOREIGN KEY FK_D3A44DC8F347EFB');
        $this->addSql('ALTER TABLE bp_info_product ADD CONSTRAINT FK_D3A44DC8F347EFB FOREIGN KEY (produit_id) REFERENCES bp_produit (id)');
        $this->addSql('ALTER TABLE bp_produit DROP FOREIGN KEY FK_FEF4ADDD85B7937E');
        $this->addSql('ALTER TABLE bp_produit ADD CONSTRAINT FK_FEF4ADDD85B7937E FOREIGN KEY (businessPlan_id) REFERENCES bp_bp (id)');

        $this->addSql('ALTER TABLE bp_info_charge DROP FOREIGN KEY FK_BB22C13489D40298');
        $this->addSql('ALTER TABLE bp_info_charge ADD CONSTRAINT FK_BB22C13489D40298 FOREIGN KEY (exercice_id) REFERENCES bp_exercice (id)');
        $this->addSql('ALTER TABLE bp_info_product DROP FOREIGN KEY FK_D3A44DC889D40298');
        $this->addSql('ALTER TABLE bp_info_product ADD CONSTRAINT FK_D3A44DC889D40298 FOREIGN KEY (exercice_id) REFERENCES bp_exercice (id)');

        $this->addSql('ALTER TABLE bp_address DROP FOREIGN KEY FK_DA1F2E7BA76ED395');
        $this->addSql('ALTER TABLE bp_address ADD CONSTRAINT FK_DA1F2E7BA76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id)');
    }
}
