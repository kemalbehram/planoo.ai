<?php declare (strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200818120059 extends AbstractMigration {
    public function up(Schema $schema): void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE promotion_catalog ADD catalogSource_id INT DEFAULT NULL, ADD catalogDestination_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promotion_catalog ADD CONSTRAINT FK_4351C47DBDA05BC FOREIGN KEY (catalogSource_id) REFERENCES promotion_catalog (id)');
        $this->addSql('ALTER TABLE promotion_catalog ADD CONSTRAINT FK_4351C47D9FB5C93B FOREIGN KEY (catalogDestination_id) REFERENCES promotion_catalog (id)');
        $this->addSql('CREATE INDEX IDX_4351C47DBDA05BC ON promotion_catalog (catalogSource_id)');
        $this->addSql('CREATE INDEX IDX_4351C47D9FB5C93B ON promotion_catalog (catalogDestination_id)');

        // Item Migration Essentiel -> Pro
        $this->addSql('INSERT INTO `promotion_catalog` (`name`, `description`, `price`, `nb_day_expire_edit_date`, `type`, `hasWording`, `buyable`, `nb_advice_hour`, `createdAt`, `updatedAt`, `deletedAt`, `catalogSource_id`, `catalogDestination_id`) VALUES
            ( "Mise à jour Formule Essentiel vers Formule Pro", "Mise à jour Formule Essentiel vers Formule Pro", 130, 0, "BP Upgrade", 0, 1, 1, sysdate(),sysdate(),null, 29, 30),
            ( "Mise à jour Formule Essentiel vers Formule Premium", "Mise à jour Formule Essentiel vers Formule Premium", 380, 0, "BP Upgrade", 1, 1, 1, sysdate(),sysdate(),null, 29, 31),
            ( "Mise à jour Formule Pro vers Formule Premium", "Mise à jour Formule Pro vers Formule Premium", 250, 0, "BP Upgrade", 1, 1, 0, sysdate(),sysdate(),null, 30, 31)
        ');

        $this->addSql('UPDATE promotion_catalog SET `name` = \'Formule Essentielle\' WHERE `promotion_catalog`.`id` = 29');
        $this->addSql('UPDATE promotion_catalog SET `name` = \'Formule Pro\' WHERE `promotion_catalog`.`id` = 30');
        $this->addSql('UPDATE promotion_catalog SET `name` = \'Formule Premium\' WHERE `promotion_catalog`.`id` = 31');
    }

    public function down(Schema $schema): void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE promotion_catalog DROP FOREIGN KEY FK_4351C47DBDA05BC');
        $this->addSql('ALTER TABLE promotion_catalog DROP FOREIGN KEY FK_4351C47D9FB5C93B');
        $this->addSql('DROP INDEX IDX_4351C47DBDA05BC ON promotion_catalog');
        $this->addSql('DROP INDEX IDX_4351C47D9FB5C93B ON promotion_catalog');
        $this->addSql('ALTER TABLE promotion_catalog DROP catalogSource_id, DROP catalogDestination_id');
    }
}
