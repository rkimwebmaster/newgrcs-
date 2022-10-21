<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014205426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ess_compte_grcs ADD COLUMN qte_diesel_non_servie INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE ess_compte_grcs ADD COLUMN qte_essence_non_servie INTEGER DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__ess_compte_grcs AS SELECT id, grand_fournisseur_id, grcs_id, quantite_diesel, quantite_essence, date_dernier_approvisionnement, is_post_paye, pourcentage_diesel, pourcentage_essence FROM ess_compte_grcs');
        $this->addSql('DROP TABLE ess_compte_grcs');
        $this->addSql('CREATE TABLE ess_compte_grcs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, grand_fournisseur_id INTEGER NOT NULL, grcs_id INTEGER NOT NULL, quantite_diesel INTEGER NOT NULL, quantite_essence INTEGER NOT NULL, date_dernier_approvisionnement DATETIME NOT NULL, is_post_paye BOOLEAN DEFAULT NULL, pourcentage_diesel DOUBLE PRECISION DEFAULT NULL, pourcentage_essence DOUBLE PRECISION DEFAULT NULL, CONSTRAINT FK_1D16BA2C766151FA FOREIGN KEY (grand_fournisseur_id) REFERENCES ess_grand_fournisseur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1D16BA2CAB8F1F12 FOREIGN KEY (grcs_id) REFERENCES ess_grcs (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ess_compte_grcs (id, grand_fournisseur_id, grcs_id, quantite_diesel, quantite_essence, date_dernier_approvisionnement, is_post_paye, pourcentage_diesel, pourcentage_essence) SELECT id, grand_fournisseur_id, grcs_id, quantite_diesel, quantite_essence, date_dernier_approvisionnement, is_post_paye, pourcentage_diesel, pourcentage_essence FROM __temp__ess_compte_grcs');
        $this->addSql('DROP TABLE __temp__ess_compte_grcs');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D16BA2C766151FA ON ess_compte_grcs (grand_fournisseur_id)');
        $this->addSql('CREATE INDEX IDX_1D16BA2CAB8F1F12 ON ess_compte_grcs (grcs_id)');
    }
}
