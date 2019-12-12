<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191118133814 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE type_utilisateur (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE utilisateur ADD mon_type_id INT NOT NULL, DROP type');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B390ACDD9F FOREIGN KEY (mon_type_id) REFERENCES type_utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B390ACDD9F ON utilisateur (mon_type_id)');
        $this->addSql('ALTER TABLE fiche_frais CHANGE montant montant DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE forfait CHANGE quantite quantite INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B390ACDD9F');
        $this->addSql('DROP TABLE type_utilisateur');
        $this->addSql('ALTER TABLE fiche_frais CHANGE montant montant DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE forfait CHANGE quantite quantite INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_1D1C63B390ACDD9F ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur ADD type VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP mon_type_id');
    }
}
