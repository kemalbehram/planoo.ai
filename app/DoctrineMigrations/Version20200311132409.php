<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200311132409 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //Conditions Générales de Vente
        $this->addSql("update back_page set updatedAt=sysdate() where id=29;");
        $this->addSql("update back_page set updatedAt=sysdate() where id=31;");
        $this->addSql("update back_page set updatedAt=sysdate() where id=32;");

        $this->addSql("update back_page_translation set content=REPLACE(content, 'SAS IZYPITCH', 'JOORNEY TECH USA LLC');");
        $this->addSql("update back_page_translation set content=REPLACE(content, 'IZYPITCH', 'JOORNEY TECH');");
        $this->addSql("update back_page_translation set content=REPLACE(content, 'Ce traitement a fait l\'objet d\'une déclaration à la CNIL sous le numéro 1865667.', '');");
        $this->addSql("update back_page_translation set content=REPLACE(content, 'SAS Izypitch 148 Boulevard de Grenelle 75015 Paris', 'Joorney Tech USA LLC, 407 Lincoln Rd Miami Beach FL 33139, USA');");

        //Politique de confidentialité
        $this->addSql("update back_page_translation set content=REPLACE(content, '148 Boulevard de Grenelle', '407 Lincoln Rd');");
        $this->addSql("update back_page_translation set content=REPLACE(content, '75015 Paris', 'Miami Beach FL 33139, USA');");
        $this->addSql("update back_page_translation set content=REPLACE(content, 'T&eacute;l&eacute;phone : +33170060542 ', '');");
        $this->addSql("update back_page_translation set content=REPLACE(content, 'compter du 15 juillet 2018', 'compter du 15 mars 2020');");

        //Mentions légales
        $this->addSql("update back_page_translation set content='<p><strong>Acc&egrave;s et consultation des sites</strong></p>
<hr />
<p>L&rsquo;acc&egrave;s au site ainsi que leur consultation supposent acceptation pleine, enti&egrave;re et sans r&eacute;serve des Conditions G&eacute;n&eacute;rales d&rsquo;Utilisation/de Vente.</p>
<p>&nbsp;</p>
<p><strong>Informations sur l&rsquo;&eacute;diteur</strong></p>
<hr />
<p>Le site &laquo;&nbsp;<a href=\"http://planoo.ai/\">planoo.ai</a>&nbsp;&raquo; est &eacute;dit&eacute; par&nbsp;:</p>
<p>Joorney Tech USA LLC&nbsp;<br />407 Lincoln Rd,<br />Miami Beach FL 33139 USA</p>
<p>Mail&nbsp;:&nbsp;<a href=\"mailto:contact@planoo.ai\">contact@planoo.ai</a>&nbsp;&nbsp;</p>
<p><strong>H&eacute;bergeur des sites</strong></p>
<hr />
<p>Le site &laquo;&nbsp;<a href=\"http://planoo.ai/\">planoo.ai</a>&nbsp;&raquo; est h&eacute;berg&eacute; par&nbsp;:</p>
<p><strong>OVH</strong><br />SAS au capital de 10&nbsp;069&nbsp;020&nbsp;&euro;&nbsp;<br />RCS Lille M&eacute;tropole 424 761 419 00045<br />Code APE 2620Z<br />N&deg; TVA : FR 22 424 761 419<br />Si&egrave;ge social : 2 rue Kellermann - 59100 Roubaix - France.</p>' where translatable_id='32'");

        $this->addSql("update back_page_translation set title=REPLACE(title, 'd\'IzyPitch', 'de Planoo');");
        $this->addSql("update back_page_translation set content=REPLACE(content, 'd\'IzyPitch', 'de Planoo');");

        $this->addSql("update back_page_translation set title=REPLACE(title, 'd\'Izypitch', 'de Planoo');");
        $this->addSql("update back_page_translation set content=REPLACE(content, 'd\'Izypitch', 'de Planoo');");

        $this->addSql("update back_page_translation set title=REPLACE(title, 'IzyPitch', 'Planoo');");
        $this->addSql("update back_page_translation set content=REPLACE(content, 'IzyPitch', 'Planoo');");

        $this->addSql("update back_page_translation set title=REPLACE(title, 'Izypitch', 'Planoo');");
        $this->addSql("update back_page_translation set content=REPLACE(content, 'Izypitch', 'Planoo');");

        $this->addSql("update back_page_translation set title=REPLACE(title, 'www.izypitch.com', 'planoo.ai');");
        $this->addSql("update back_page_translation set content=REPLACE(content, 'www.izypitch.com', 'planoo.ai');");

        $this->addSql("update back_page_translation set title=REPLACE(title, 'izypitch.com', 'planoo.ai');");
        $this->addSql("update back_page_translation set content=REPLACE(content, 'izypitch.com', 'planoo.ai');");

        $this->addSql("update back_page_translation set title=REPLACE(title, '@izypitch.com', '@planoo.ai');");
        $this->addSql("update back_page_translation set content=REPLACE(content, '@izypitch.com', '@planoo.ai');");

        $this->addSql("update back_page_translation set title=REPLACE(title, 'Izypitch', 'Planoo');");
        $this->addSql("update back_page_translation set content=REPLACE(content, 'Izypitch', 'Planoo');");


        $this->addSql("update back_product set link=REPLACE(link, 'Izypitch_BP.pdf', 'Planoo_BP.pdf');");

        $this->addSql("update back_product_translation set description=REPLACE(description, 'd\'IzyPitch', 'de Planoo');");
        $this->addSql("update back_product_translation set content=REPLACE(content, 'd\'IzyPitch', 'de Planoo');");

        $this->addSql("update back_product_translation set description=REPLACE(description, 'd\'Izypitch', 'de Planoo');");
        $this->addSql("update back_product_translation set content=REPLACE(content, 'd\'Izypitch', 'de Planoo');");

        $this->addSql("update back_product_translation set description=REPLACE(description, 'IzyPitch', 'Planoo');");
        $this->addSql("update back_product_translation set content=REPLACE(content, 'IzyPitch', 'Planoo');");

        $this->addSql("update back_product_translation set description=REPLACE(description, 'Izypitch', 'Planoo');");
        $this->addSql("update back_product_translation set content=REPLACE(content, 'Izypitch', 'Planoo');");

        $this->addSql("update back_testimonials set content=REPLACE(content, 'd\'IzyPitch', 'de Planoo');");
        $this->addSql("update back_testimonials set content=REPLACE(content, 'd\'Izypitch', 'de Planoo');");
        $this->addSql("update back_testimonials set content=REPLACE(content, 'IzyPitch', 'Planoo');");
        $this->addSql("update back_testimonials set content=REPLACE(content, 'Izypitch', 'Planoo');");

        $this->addSql("update blog_post set author=REPLACE(author, 'd\'IzyPitch', 'de Planoo');");
        $this->addSql("update blog_post set author=REPLACE(author, 'd\'Izypitch', 'de Planoo');");
        $this->addSql("update blog_post set author=REPLACE(author, 'IzyPitch', 'Planoo');");
        $this->addSql("update blog_post set author=REPLACE(author, 'Izypitch', 'Planoo');");

        $this->addSql("delete from blog_post where id in (9,10,11,12,13,14,15);");

        $this->addSql("update post_translation set content=REPLACE(content, 'd\'IzyPitch', 'de Planoo');");
        $this->addSql("update post_translation set content=REPLACE(content, 'd\'Izypitch', 'de Planoo');");
        $this->addSql("update post_translation set content=REPLACE(content, 'IzyPitch', 'Planoo');");
        $this->addSql("update post_translation set content=REPLACE(content, 'Izypitch', 'Planoo');");

        $this->addSql("update post_translation set content=REPLACE(content, 'http://', 'https://');");
        $this->addSql("update post_translation set content=REPLACE(content, 'www.izypitch.com', 'planoo.ai');");

        $this->addSql("delete from post_translation where id in (9,10,11,12,13,14,15);");

        $this->addSql("update page set info=REPLACE(info, 'd\'IzyPitch', 'de Planoo');");
        $this->addSql("update page set info=REPLACE(info, 'd\'Izypitch', 'de Planoo');");
        $this->addSql("update page set info=REPLACE(info, 'IzyPitch', 'Planoo');");
        $this->addSql("update page set info=REPLACE(info, 'Izypitch', 'Planoo');");

        $this->addSql("update partners_partner set customDomain=REPLACE(customDomain, 'izypitch.com', 'planoo.ai');");
        $this->addSql("update partners_partner set nom='planoo' where nom='izypitch';");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
    }

}
