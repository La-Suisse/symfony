<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191118133138 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, identifiant VARCHAR(100) NOT NULL, mdp VARCHAR(100) NOT NULL, type VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hors_forfait (id INT AUTO_INCREMENT NOT NULL, ma_fiche_id INT NOT NULL, date DATE NOT NULL, libelle VARCHAR(128) NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_7EAEE44D979715B (ma_fiche_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_frais (id INT AUTO_INCREMENT NOT NULL, mon_etat_id INT NOT NULL, mon_utilisateur_id INT NOT NULL, date DATE NOT NULL, montant DOUBLE PRECISION DEFAULT NULL, INDEX IDX_5FC0A6A758BED7F3 (mon_etat_id), INDEX IDX_5FC0A6A72FFD6826 (mon_utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forfait (id INT AUTO_INCREMENT NOT NULL, mon_type_id INT NOT NULL, ma_fiche_id INT NOT NULL, quantite INT DEFAULT NULL, INDEX IDX_BBB5C48290ACDD9F (mon_type_id), INDEX IDX_BBB5C482D979715B (ma_fiche_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_frais (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(128) NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hors_forfait ADD CONSTRAINT FK_7EAEE44D979715B FOREIGN KEY (ma_fiche_id) REFERENCES fiche_frais (id)');
        $this->addSql('ALTER TABLE fiche_frais ADD CONSTRAINT FK_5FC0A6A758BED7F3 FOREIGN KEY (mon_etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE fiche_frais ADD CONSTRAINT FK_5FC0A6A72FFD6826 FOREIGN KEY (mon_utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE forfait ADD CONSTRAINT FK_BBB5C48290ACDD9F FOREIGN KEY (mon_type_id) REFERENCES type_frais (id)');
        $this->addSql('ALTER TABLE forfait ADD CONSTRAINT FK_BBB5C482D979715B FOREIGN KEY (ma_fiche_id) REFERENCES fiche_frais (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fiche_frais DROP FOREIGN KEY FK_5FC0A6A72FFD6826');
        $this->addSql('ALTER TABLE hors_forfait DROP FOREIGN KEY FK_7EAEE44D979715B');
        $this->addSql('ALTER TABLE forfait DROP FOREIGN KEY FK_BBB5C482D979715B');
        $this->addSql('ALTER TABLE forfait DROP FOREIGN KEY FK_BBB5C48290ACDD9F');
        $this->addSql('ALTER TABLE fiche_frais DROP FOREIGN KEY FK_5FC0A6A758BED7F3');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE hors_forfait');
        $this->addSql('DROP TABLE fiche_frais');
        $this->addSql('DROP TABLE forfait');
        $this->addSql('DROP TABLE type_frais');
        $this->addSql('DROP TABLE etat');
    }
}
