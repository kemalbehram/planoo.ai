<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201021093024 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE promotion_catalog ADD pickupline VARCHAR(255) NOT NULL, ADD highlight TINYINT(1) DEFAULT \'0\' NOT NULL');

        // Update BP Main Formula
        $this->addSql('UPDATE `promotion_catalog` SET `highlight` = 1 WHERE `promotion_catalog`.`id` = 30');

        // Update BP Fomulae
        $this->addSql('UPDATE `promotion_catalog` SET `pickupline` = \'Décrochez votre prêt bancaire\', `description` = \'<div>
        <span class="izy-color-secondary-font"><strong>Business Plan Financier 3 ans</strong></span>
        <ul><li><strong>Compte de r&eacute;sultat :</strong></li></ul>
        <p>Chiffre d&rsquo;affaires, Marge Brute, Charges, Charges de personnel, Rentabilit&eacute;</p>
        <ul><li><strong>Bilan et pr&eacute;vision de tr&eacute;sorerie :</strong></li></ul>
        <p>Investissements, Amortissements, Dettes financi&egrave;res, Pr&eacute;visionnel de tr&eacute;sorerie</p>
        <ul><li><strong>Etat financiers mensuel</strong></li></ul></div>\' WHERE `promotion_catalog`.`id` = 29');
        $this->addSql('UPDATE `promotion_catalog` SET `pickupline` = \'Etre accompagné pour décrocher votre financement\', `description` = \'<div>
        <span class="izy-color-secondary-font"><strong>Business Plan Financier  3 à 5 ans</strong></span>
        <ul><li><strong>Compte de r&eacute;sultat :</strong></li></ul>
        <p>Chiffre d&rsquo;affaires, Marge Brute, Charges, Charges de personnel, Rentabilit&eacute;</p>
        <ul><li><strong>Bilan et pr&eacute;vision de tr&eacute;sorerie :</strong></li></ul>
        <p>Investissements, Amortissements, Dettes financi&egrave;res, Pr&eacute;visionnel de tr&eacute;sorerie</p>
        <ul><li><strong>Etat financiers mensuel</strong></li></ul>
        <span class="izy-color-secondary-font" class="pull_low"><strong>Accompagnement Financier</strong></span>
        <ul><li><strong>1H00 de conseil financier avec un expert par t&eacute;l&eacute;phone</strong></li></ul>
        </div>\' WHERE `promotion_catalog`.`id` = 30');

        $this->addSql('UPDATE `promotion_catalog` SET `pickupline` = \'Soyez convaiquant et décrochez votre financement\', `description` = \'<div>
        <span class="izy-color-secondary-font"><strong>Business Plan Financier  3 à 5 ans</strong></span>
        <ul><li><strong>Compte de r&eacute;sultat :</strong></li></ul>
        <p>Chiffre d&rsquo;affaires, Marge Brute, Charges, Charges de personnel, Rentabilit&eacute;</p>
        <ul><li><strong>Bilan et pr&eacute;vision de tr&eacute;sorerie :</strong></li></ul>
        <p>Investissements, Amortissements, Dettes financi&egrave;res, Pr&eacute;visionnel de tr&eacute;sorerie</p>
        <ul><li><strong>Etat financiers mensuel</strong></li></ul>
        <span class="izy-color-secondary-font" class="pull_low"><strong>Accompagnement Financier</strong></span>
        <ul><li><strong>1H00 de conseil financier avec un expert par t&eacute;l&eacute;phone</strong></li></ul>
        <span class="izy-color-secondary-font" class="pull_low"><strong>Pr&eacute;sentation de projet</strong></span>
        <ul><li><strong>R&eacute;daction personnalis&eacute;e de projet</strong></li></ul>
        <p>Produits et services, Proposition de valeur, Cibles, Strat&eacute;gie commerciale, Strat&eacute;gie de distribution, Strat&eacute;gie de communication, Equipe, Besoin de financement</p>
        <ul><li><strong>Etude de march&eacute; sectorielle</strong></li></ul>
        <p>Evolution secteur : Informations et chiffres cl&eacute;s de votre secteur d&rsquo;activit&eacute; (Croissance de votre secteur, les d&eacute;terminants de l&rsquo;activit&eacute;, l&eacute;gislation ...)</p>
        </div>\' WHERE `promotion_catalog`.`id` = 31');
        
        // Update BP Upgrade
        $this->addSql('UPDATE `promotion_catalog` SET `pickupline` = \'Soyez conseillé pour décrocher votre financement\', `description` = \'<div>
        <p class="no-padding"><strong>Vous avez réaliser votre Business Plan Financier et vous souhaitez échanger avec expert pour mettre toutes les chances de votre coté pour décrocher votre financement ?<strong></p>
        <span class="izy-color-secondary-font" class="pull_low"><strong>1H00 de conseil Financier avec un expert en Business Plan par téléphone</strong></span>
        <p class="no-padding no-margin-bottom">Posez toutes vos questions et obtenez des réponses concrètes sur : </p>
        <ul>
        <li><strong>Les chiffres de votre Business Plan</strong></li>
        <li><strong>La cohérence de vos chiffres par rapport à votre secteur</strong></li>
        <li><strong>Les arguments pour défendre vos chiffres auprès de vos partenaires</strong></li>
        </ul>
        </div>\' WHERE `promotion_catalog`.`id` = 43');
        $this->addSql('UPDATE `promotion_catalog` SET `pickupline` = \'Soyez convaincant et décrochez votre financement\', `description` = \'<div>
        <p class="no-padding"><strong>Vous avez réaliser votre Business Plan Financier, vous souhaitez également avoir une présentation détaillée de votre projet et les conseil d\’un expert pour décrocher votre financement ?</strong></p>
        <span class="izy-color-secondary-font" class="pull_low"><strong>1H00 de conseil Financier avec un expert en Business Plan par téléphone</strong></span>
        <ul>
        <li><strong>Posez toutes vos questions et obtenez des réponses concrêtes</strong></li>
        </ul>
        <span class="izy-color-secondary-font" class="pull_low"><strong>Présentation personnalisée de votre projet</strong></span>
        <ul><li><strong>Rédaction personnalisée de projet</strong></li></ul>
        <p>Produits et services, Proposition de valeur, Cibles, Stratégie commerciale, Stratégie de distribution, Stratégie de communication, Equipe, Besoin de financement</p> 
        <ul><li><strong>Etude de marché sectorielle et locale</strong></li></ul>
        <p>Evolution secteur : Informations et chiffres clés de votre secteur d\’activité (Croissance de votre secteur, les déterminants de l\’activité, …)<br/>Etude locale : données démographiques, principaux concurrents (dénomination, CA, </p>
        </div>\' WHERE `promotion_catalog`.`id` = 44');
        $this->addSql('UPDATE `promotion_catalog` SET `pickupline` = \'Soyez convaincant  et décrochez votre financement\', `description` = \'<div>
        <p class="no-padding"><strong>Vous avez réaliser votre Business Plan Financier et échangez sur toutes vos questions ? Il ne manque plus que la présentation de votre projet et une étude de marché pour convaincre vos partenaires ?</strong></p>
        <span class="izy-color-secondary-font" class="pull_low"><strong>Présentation personnalisée de votre projet</strong></span>
        <ul><li><strong>Rédaction personnalisée de projet</strong></li></ul>
        <p>Produits et services, Proposition de valeur, Cibles, Stratégie commerciale, Stratégie de distribution, Stratégie de communication, Equipe, Besoin de financement</p>
        <ul><li><strong>Etude de marché sectorielle et locale</strong></li></ul>
        <p>Evolution secteur : Informations et chiffres clés de votre secteur d\’activité (Croissance de votre secteur, les déterminants de l\’activité, …)<br/>Etude locale : données démographiques, principaux concurrents (dénomination, CA, </p>
        </div>\' WHERE `promotion_catalog`.`id` = 45');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE promotion_catalog DROP pickupline, DROP highlight');
    }
}
