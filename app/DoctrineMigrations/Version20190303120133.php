<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190303120133 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_info_product ADD caExercice DOUBLE PRECISION DEFAULT NULL, ADD coutExercice DOUBLE PRECISION DEFAULT NULL, CHANGE nbVente nbVente DOUBLE PRECISION DEFAULT NULL, CHANGE prixVente prixVente DOUBLE PRECISION DEFAULT NULL, CHANGE cout cout DOUBLE PRECISION DEFAULT NULL');

        $this->addSql('UPDATE bp_info_product SET caExercice=nbVente*prixVente');
        $this->addSql('UPDATE bp_info_product SET coutExercice=nbVente*cout');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_info_product DROP caExercice, DROP coutExercice, CHANGE nbVente nbVente DOUBLE PRECISION NOT NULL, CHANGE prixVente prixVente DOUBLE PRECISION NOT NULL, CHANGE cout cout DOUBLE PRECISION NOT NULL');
    }

}
