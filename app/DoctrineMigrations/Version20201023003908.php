<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201023003908 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE page ADD description LONGTEXT DEFAULT NULL, ADD video_url LONGTEXT DEFAULT NULL');
        $this->addSql('UPDATE page SET description="Remplissez les informations de présentation de votre projet et de votre entreprise.	",video_url="https://share.vidyard.com/watch/HAdq761d4qgJRSTK3Wzfdo?"  where step=1');
        $this->addSql('UPDATE page SET description="Renseignez les principales informations de votre entreprise afin de bien paramétrer votre document. ",video_url="https://share.vidyard.com/watch/J1G9gc3mxfvk4q14qrCas1?"  where step=2');
        $this->addSql('UPDATE page SET description="Indiquez la saisonnalité de votre activité. Si votre activité connait une forte saisonnalité vous renseignez cette dernière en indiquant les mois pleins (100 % d\'activité) et les mois creux (0% d\'activité).",video_url="https://share.vidyard.com/watch/CFZkiY5yqAaCFwyeRuGCe5?"  where step=3');
        $this->addSql('UPDATE page SET description="Construisez votre chiffre d\'affaires HT (par produit si vous le désirez) et vous indiquez vos marges prévisionnelles.",video_url="https://share.vidyard.com/watch/i2HY7YvmRDPucNhfqL9w7Z?"  where step=4');
        $this->addSql('UPDATE page SET description="Listez toutes les charges de fonctionnement de votre entreprise c\'est-à-dire toutes les dépenses (hors personnel et coûts directs des produits, ces derniers étant comptabilisés dans le taux de marge précédemment renseigné) que votre entreprise devra supporter en HT.",video_url="https://share.vidyard.com/watch/BNXn52JBHQs1u6TcXANZF7?"  where step=5');
        $this->addSql('UPDATE page SET description="Indiquez les besoins humains de votre société (dirigeant compris) c’est-à-dire le poste et la rémunération mensuelle nette de vos futurs salariés.",video_url="https://share.vidyard.com/watch/765cGpgAhHm9BQ57UpgPtN?"  where step=6');
        $this->addSql('UPDATE page SET description="Renseignez les délais de règlement de vos clients et de vos fournisseurs mais également votre stock afin de calculer automatiquement votre Besoin en Fonds de Roulement (BFR). Le Besoin en Fonds de Roulement correspond au décalage entre le moment où vos clients vous règlent et le moment où vous réglez vos fournisseurs.",video_url="https://share.vidyard.com/watch/X7jk2xrQeymnCKr9wGYXHR?"  where step=7');
        $this->addSql('UPDATE page SET description="Listez les investissements HT nécessaires pour démarrer et développer votre activité.",video_url="https://share.vidyard.com/watch/oGycksZG6tCAou2guWJ5rK?"  where step=8');
        $this->addSql('UPDATE page SET description="Construisez votre plan de financement en indiquant les différents financements auquels vous allez avoir recours (apport personnel, emprunt bancaire, investisseurs…).",video_url="https://share.vidyard.com/watch/xw9qdbLgc5SR7y1hsBBzyM?"  where step=9');

        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE page DROP description, DROP video_url');
    }
}
