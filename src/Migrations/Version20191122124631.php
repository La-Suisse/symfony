<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191122124631 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fiche_frais_forfait (fiche_frais_id INT NOT NULL, forfait_id INT NOT NULL, INDEX IDX_E0A56B90D94F5755 (fiche_frais_id), INDEX IDX_E0A56B90906D5F2C (forfait_id), PRIMARY KEY(fiche_frais_id, forfait_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_frais_hors_forfait (fiche_frais_id INT NOT NULL, hors_forfait_id INT NOT NULL, INDEX IDX_4DAB041AD94F5755 (fiche_frais_id), INDEX IDX_4DAB041A91F0352F (hors_forfait_id), PRIMARY KEY(fiche_frais_id, hors_forfait_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fiche_frais_forfait ADD CONSTRAINT FK_E0A56B90D94F5755 FOREIGN KEY (fiche_frais_id) REFERENCES fiche_frais (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fiche_frais_forfait ADD CONSTRAINT FK_E0A56B90906D5F2C FOREIGN KEY (forfait_id) REFERENCES forfait (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fiche_frais_hors_forfait ADD CONSTRAINT FK_4DAB041AD94F5755 FOREIGN KEY (fiche_frais_id) REFERENCES fiche_frais (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fiche_frais_hors_forfait ADD CONSTRAINT FK_4DAB041A91F0352F FOREIGN KEY (hors_forfait_id) REFERENCES hors_forfait (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fiche_frais CHANGE montant montant DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE forfait CHANGE quantite quantite INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE fiche_frais_forfait');
        $this->addSql('DROP TABLE fiche_frais_hors_forfait');
        $this->addSql('ALTER TABLE fiche_frais CHANGE montant montant DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE forfait CHANGE quantite quantite INT DEFAULT NULL');
    }
}
