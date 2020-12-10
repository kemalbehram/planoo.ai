<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200727202548 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_charge CHANGE $tva $tva DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE bp_information CHANGE tva tva DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE bp_produit CHANGE tvaAchats tvaAchats DOUBLE PRECISION DEFAULT NULL, CHANGE $tvaVentes $tvaVentes DOUBLE PRECISION DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_charge CHANGE $tva $tva INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bp_information CHANGE tva tva INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bp_produit CHANGE tvaAchats tvaAchats INT DEFAULT NULL, CHANGE $tvaVentes $tvaVentes INT DEFAULT NULL');
    }
}