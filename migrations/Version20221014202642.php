<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014202642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__ess_ticket AS SELECT id, utilisateur_id, compte_petit_client_id, date, nom_chauffeur, numero_plaque_immatriculation, is_groupe_electrogene, date_retrait, quantite, is_credit, total_montant, is_servi, type_carburant, is_diesel, created_at FROM ess_ticket');
        $this->addSql('DROP TABLE ess_ticket');
        $this->addSql('CREATE TABLE ess_ticket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, compte_petit_client_id INTEGER NOT NULL, date DATETIME NOT NULL, nom_chauffeur VARCHAR(255) DEFAULT NULL, numero_plaque_immatriculation VARCHAR(255) DEFAULT NULL, is_groupe_electrogene BOOLEAN NOT NULL, date_retrait DATETIME NOT NULL, quantite INTEGER NOT NULL, is_credit BOOLEAN NOT NULL, total_montant DOUBLE PRECISION NOT NULL, is_servi BOOLEAN NOT NULL, type_carburant VARCHAR(255) NOT NULL, is_diesel BOOLEAN DEFAULT NULL, created_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_CC4A5B7FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES ess_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CC4A5B7F268DB0EF FOREIGN KEY (compte_petit_client_id) REFERENCES ess_compte_petit_client (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ess_ticket (id, utilisateur_id, compte_petit_client_id, date, nom_chauffeur, numero_plaque_immatriculation, is_groupe_electrogene, date_retrait, quantite, is_credit, total_montant, is_servi, type_carburant, is_diesel, created_at) SELECT id, utilisateur_id, compte_petit_client_id, date, nom_chauffeur, numero_plaque_immatriculation, is_groupe_electrogene, date_retrait, quantite, is_credit, total_montant, is_servi, type_carburant, is_diesel, created_at FROM __temp__ess_ticket');
        $this->addSql('DROP TABLE __temp__ess_ticket');
        $this->addSql('CREATE INDEX IDX_CC4A5B7F268DB0EF ON ess_ticket (compte_petit_client_id)');
        $this->addSql('CREATE INDEX IDX_CC4A5B7FFB88E14F ON ess_ticket (utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__ess_ticket AS SELECT id, utilisateur_id, compte_petit_client_id, date, nom_chauffeur, numero_plaque_immatriculation, is_groupe_electrogene, date_retrait, quantite, is_credit, total_montant, is_servi, type_carburant, is_diesel, created_at FROM ess_ticket');
        $this->addSql('DROP TABLE ess_ticket');
        $this->addSql('CREATE TABLE ess_ticket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, compte_petit_client_id INTEGER NOT NULL, date DATETIME NOT NULL, nom_chauffeur VARCHAR(255) DEFAULT NULL, numero_plaque_immatriculation VARCHAR(255) DEFAULT NULL, is_groupe_electrogene BOOLEAN NOT NULL, date_retrait DATETIME NOT NULL, quantite INTEGER NOT NULL, is_credit BOOLEAN NOT NULL, total_montant DOUBLE PRECISION NOT NULL, is_servi BOOLEAN NOT NULL, type_carburant VARCHAR(255) NOT NULL, is_diesel BOOLEAN DEFAULT NULL, created_at DATETIME DEFAULT NULL, CONSTRAINT FK_CC4A5B7FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES "ess_user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CC4A5B7F268DB0EF FOREIGN KEY (compte_petit_client_id) REFERENCES ess_compte_petit_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ess_ticket (id, utilisateur_id, compte_petit_client_id, date, nom_chauffeur, numero_plaque_immatriculation, is_groupe_electrogene, date_retrait, quantite, is_credit, total_montant, is_servi, type_carburant, is_diesel, created_at) SELECT id, utilisateur_id, compte_petit_client_id, date, nom_chauffeur, numero_plaque_immatriculation, is_groupe_electrogene, date_retrait, quantite, is_credit, total_montant, is_servi, type_carburant, is_diesel, created_at FROM __temp__ess_ticket');
        $this->addSql('DROP TABLE __temp__ess_ticket');
        $this->addSql('CREATE INDEX IDX_CC4A5B7FFB88E14F ON ess_ticket (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_CC4A5B7F268DB0EF ON ess_ticket (compte_petit_client_id)');
    }
}
