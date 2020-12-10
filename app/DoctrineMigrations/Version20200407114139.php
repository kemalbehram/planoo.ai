<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200407114139 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bp_activity (id INT AUTO_INCREMENT NOT NULL, category VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, isMarketStudyAvailable TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_F1CB43195E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bp_information ADD activity VARCHAR(255) NOT NULL');
        $this->addSql('INSERT INTO bp_activity (category,name,isMarketStudyAvailable) VALUES
            (\'Service à la personne\',\'Aide à domicile\',0),
            (\'Profession libérale\',\'Architecte\',1),
            (\'Transport\',\'Auto-école\',1),
            (\'Débit de boisson\',\'Bar - cafés\',1),
            (\'Hotellerie - restauration\',\'Bar - restaurant\',0),
            (\'Métiers de bouche\',\'Boucherie\',1),
            (\'Métiers de bouche\',\'Boulangerie - patisserie\',1),
            (\'Artisanat\',\'Electricité\',1),
            (\'Hotellerie - restauration\',\'Food Truck\',0),
            (\'Transport\',\'Garage automobile\',1),
            (\'Profession libérale\',\'Infirmlière libérale\',0),
            (\'Beauté\',\'Institut de beauté\',1),
            (\'Artisanat\',\'Maçonnerie\',1),
            (\'Artisanat\',\'Menuiserie\',0),
            (\'Beauté\',\'Onglerie\',1),
            (\'Artisanat\',\'Plomberie\',1),
            (\'Hotellerie - restauration\',\'Restauration rapide\',1),
            (\'Hotellerie - restauration\',\'Restauration traditionnelle\',1),
            (\'Beauté\',\'Salon de coiffure\',1),
            (\'Beauté\',\'Tatoueur\',0),
            (\'Transport\',\'Taxi\',1),
            (\'E-commerce\',\'Vente en ligne\',0),
            (\'Transport\',\'VTC\',1),
            (\'Artisanat\',\'Fabrication portes et fenêtres en bois\',1),
            (\'Hotellerie - restauration\',\'Hotellerie\',1),
            (\'Profession libérale\',\'Infirmier libéral\',1),
            (\'Textile\',\'Prêt à porter\',1),
            (\'Z\',\'Autre\',0);'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE bp_activity');
        $this->addSql('ALTER TABLE bp_information DROP activity');
    }

}
