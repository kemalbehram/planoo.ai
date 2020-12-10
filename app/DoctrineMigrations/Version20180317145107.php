<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180317145107 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pitch_business_model DROP FOREIGN KEY FK_7FEB5156FEEFC64B');
        $this->addSql('ALTER TABLE pitch_cart DROP FOREIGN KEY FK_3449185FEEFC64B');
        $this->addSql('ALTER TABLE pitch_concurrence DROP FOREIGN KEY FK_4AEC9B0FFEEFC64B');
        $this->addSql('ALTER TABLE pitch_indicator DROP FOREIGN KEY FK_43B0226DFEEFC64B');
        $this->addSql('ALTER TABLE pitch_information DROP FOREIGN KEY FK_6D1CCC8BFEEFC64B');
        $this->addSql('ALTER TABLE pitch_strategy DROP FOREIGN KEY FK_42770021FEEFC64B');
        $this->addSql('ALTER TABLE pitch_swot DROP FOREIGN KEY FK_B49A75C4FEEFC64B');
        $this->addSql('ALTER TABLE pitch_team DROP FOREIGN KEY FK_CC07BF2DFEEFC64B');
        $this->addSql('ALTER TABLE promotion_catalog_pitch DROP FOREIGN KEY FK_7E3591FFEEFC64B');
        $this->addSql('ALTER TABLE pitch_competitor DROP FOREIGN KEY FK_812C40C6808BBBF5');
        $this->addSql('DROP TABLE pitch');
        $this->addSql('DROP TABLE pitch_business_model');
        $this->addSql('DROP TABLE pitch_cart');
        $this->addSql('DROP TABLE pitch_competitor');
        $this->addSql('DROP TABLE pitch_concurrence');
        $this->addSql('DROP TABLE pitch_indicator');
        $this->addSql('DROP TABLE pitch_information');
        $this->addSql('DROP TABLE pitch_strategy');
        $this->addSql('DROP TABLE pitch_swot');
        $this->addSql('DROP TABLE pitch_team');
        $this->addSql('DROP TABLE promotion_catalog_pitch');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pitch (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, document VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, state VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, price DOUBLE PRECISION DEFAULT NULL, step INT DEFAULT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_279FBED9989D9B62 (slug), UNIQUE INDEX UNIQ_279FBED95E237E06 (name), INDEX IDX_279FBED9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pitch_business_model (id INT AUTO_INCREMENT NOT NULL, pitch_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, type VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, price DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_7FEB5156FEEFC64B (pitch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pitch_cart (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, pitch_id INT DEFAULT NULL, card VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, month VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, year VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, cvc VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, service VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, stripe_token VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_3449185FEEFC64B (pitch_id), INDEX IDX_3449185A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pitch_competitor (id INT AUTO_INCREMENT NOT NULL, concurrence_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, logo VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, firstValue DOUBLE PRECISION NOT NULL, secondValue DOUBLE PRECISION NOT NULL, INDEX IDX_812C40C6808BBBF5 (concurrence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pitch_concurrence (id INT AUTO_INCREMENT NOT NULL, pitch_id INT DEFAULT NULL, indicatorOneLabel VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, indicatorOneValue DOUBLE PRECISION NOT NULL, indicatorTwoLabel VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, indicatorTwoValue DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_4AEC9B0FFEEFC64B (pitch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pitch_indicator (id INT AUTO_INCREMENT NOT NULL, pitch_id INT DEFAULT NULL, name1 VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, name2 VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, name3 VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, name4 VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, quantity1 DOUBLE PRECISION NOT NULL, quantity2 DOUBLE PRECISION NOT NULL, quantity3 DOUBLE PRECISION NOT NULL, quantity4 DOUBLE PRECISION NOT NULL, unity1 VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, unity2 VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, unity3 VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, unity4 VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_43B0226DFEEFC64B (pitch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pitch_information (id INT AUTO_INCREMENT NOT NULL, pitch_id INT DEFAULT NULL, enterprise VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, address VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, owner VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, logo VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, color VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, concept LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, market LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, target LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, trust LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, size_market LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, legalForm_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_6D1CCC8BFEEFC64B (pitch_id), INDEX IDX_6D1CCC8B854E9F19 (legalForm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pitch_strategy (id INT AUTO_INCREMENT NOT NULL, pitch_id INT DEFAULT NULL, communication LONGTEXT NOT NULL COLLATE utf8_unicode_ci, rh LONGTEXT NOT NULL COLLATE utf8_unicode_ci, customer LONGTEXT NOT NULL COLLATE utf8_unicode_ci, development LONGTEXT NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_42770021FEEFC64B (pitch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pitch_swot (id INT AUTO_INCREMENT NOT NULL, pitch_id INT DEFAULT NULL, strength_description LONGTEXT NOT NULL COLLATE utf8_unicode_ci, strength_resume VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, weakness_description LONGTEXT NOT NULL COLLATE utf8_unicode_ci, weakness_resume VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, opportunity_description LONGTEXT NOT NULL COLLATE utf8_unicode_ci, opportunity_resume VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, menace_description LONGTEXT NOT NULL COLLATE utf8_unicode_ci, menace_resume VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_B49A75C4FEEFC64B (pitch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pitch_team (id INT AUTO_INCREMENT NOT NULL, pitch_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, familyName VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, job VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, createdAt DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, avatar VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_CC07BF2DFEEFC64B (pitch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_catalog_pitch (pitch_id INT NOT NULL, catalog_id INT NOT NULL, INDEX IDX_7E3591FFEEFC64B (pitch_id), INDEX IDX_7E3591FCC3C66FC (catalog_id), PRIMARY KEY(pitch_id, catalog_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pitch ADD CONSTRAINT FK_279FBED9A76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id)');
        $this->addSql('ALTER TABLE pitch_business_model ADD CONSTRAINT FK_7FEB5156FEEFC64B FOREIGN KEY (pitch_id) REFERENCES pitch (id)');
        $this->addSql('ALTER TABLE pitch_cart ADD CONSTRAINT FK_3449185A76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id)');
        $this->addSql('ALTER TABLE pitch_cart ADD CONSTRAINT FK_3449185FEEFC64B FOREIGN KEY (pitch_id) REFERENCES pitch (id)');
        $this->addSql('ALTER TABLE pitch_competitor ADD CONSTRAINT FK_812C40C6808BBBF5 FOREIGN KEY (concurrence_id) REFERENCES pitch_concurrence (id)');
        $this->addSql('ALTER TABLE pitch_concurrence ADD CONSTRAINT FK_4AEC9B0FFEEFC64B FOREIGN KEY (pitch_id) REFERENCES pitch (id)');
        $this->addSql('ALTER TABLE pitch_indicator ADD CONSTRAINT FK_43B0226DFEEFC64B FOREIGN KEY (pitch_id) REFERENCES pitch (id)');
        $this->addSql('ALTER TABLE pitch_information ADD CONSTRAINT FK_6D1CCC8B854E9F19 FOREIGN KEY (legalForm_id) REFERENCES bp_legal_form (id)');
        $this->addSql('ALTER TABLE pitch_information ADD CONSTRAINT FK_6D1CCC8BFEEFC64B FOREIGN KEY (pitch_id) REFERENCES pitch (id)');
        $this->addSql('ALTER TABLE pitch_strategy ADD CONSTRAINT FK_42770021FEEFC64B FOREIGN KEY (pitch_id) REFERENCES pitch (id)');
        $this->addSql('ALTER TABLE pitch_swot ADD CONSTRAINT FK_B49A75C4FEEFC64B FOREIGN KEY (pitch_id) REFERENCES pitch (id)');
        $this->addSql('ALTER TABLE pitch_team ADD CONSTRAINT FK_CC07BF2DFEEFC64B FOREIGN KEY (pitch_id) REFERENCES pitch (id)');
        $this->addSql('ALTER TABLE promotion_catalog_pitch ADD CONSTRAINT FK_7E3591FCC3C66FC FOREIGN KEY (catalog_id) REFERENCES promotion_catalog (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_catalog_pitch ADD CONSTRAINT FK_7E3591FFEEFC64B FOREIGN KEY (pitch_id) REFERENCES pitch (id) ON DELETE CASCADE');
    }
}
