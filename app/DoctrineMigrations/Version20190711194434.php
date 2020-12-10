<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190711194434 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_legal_form ADD canBeIR TINYINT(1) DEFAULT \'0\' NOT NULL, ADD canBeIS TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('UPDATE bp_legal_form SET canBeIS=true, canBeIR=true WHERE code=\'SA\'');
        $this->addSql('UPDATE bp_legal_form SET canBeIS=true, canBeIR=true WHERE code=\'SAS\'');
        $this->addSql('UPDATE bp_legal_form SET canBeIS=true, canBeIR=true WHERE code=\'SASU\'');
        $this->addSql('UPDATE bp_legal_form SET canBeIS=true, canBeIR=true WHERE code=\'SARL\'');
        $this->addSql('UPDATE bp_legal_form SET canBeIS=true, canBeIR=true WHERE code=\'EURL\'');
        $this->addSql('UPDATE bp_legal_form SET canBeIS=true, canBeIR=true WHERE code=\'SCI\'');

        $this->addSql('INSERT INTO bp_legal_form (name,code,canBeIS,canBeIR) VALUES (\'Profession libérale\',\'PL\',false, true)');

        $this->addSql('INSERT INTO legal_form_country(id_legal_form, id_country) VALUES ((SELECT id FROM bp_legal_form WHERE code = \'PL\'),1)');
        $this->addSql('INSERT INTO legal_form_country(id_legal_form, id_country) VALUES ((SELECT id FROM bp_legal_form WHERE code = \'PL\'),2)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bp_legal_form DROP canBeIR, DROP canBeIS');

        $this->addSql('DELETE bp_legal_form WHERE code = \'PL\'');
    }

}
