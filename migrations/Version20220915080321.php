<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220915080321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ess_compte_grcs ADD COLUMN pourcentage_diesel DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE ess_compte_grcs ADD COLUMN pourcentage_essence DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE ess_compte_petit_client ADD COLUMN pourcentage_diesel DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE ess_compte_petit_client ADD COLUMN pourcentage_essence DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE ess_grcs ADD COLUMN qte_stock_recommande_diesel INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE ess_grcs ADD COLUMN qte_stock_recommande_essence INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE ess_petit_client ADD COLUMN qte_stock_recommande_diesel INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE ess_petit_client ADD COLUMN qte_stock_recommande_essence INTEGER DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__ess_compte_grcs AS SELECT id, grand_fournisseur_id, grcs_id, quantite_diesel, quantite_essence, date_dernier_approvisionnement, is_post_paye FROM ess_compte_grcs');
        $this->addSql('DROP TABLE ess_compte_grcs');
        $this->addSql('CREATE TABLE ess_compte_grcs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, grand_fournisseur_id INTEGER NOT NULL, grcs_id INTEGER NOT NULL, quantite_diesel INTEGER NOT NULL, quantite_essence INTEGER NOT NULL, date_dernier_approvisionnement DATETIME NOT NULL, is_post_paye BOOLEAN DEFAULT NULL, CONSTRAINT FK_1D16BA2C766151FA FOREIGN KEY (grand_fournisseur_id) REFERENCES ess_grand_fournisseur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1D16BA2CAB8F1F12 FOREIGN KEY (grcs_id) REFERENCES ess_grcs (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ess_compte_grcs (id, grand_fournisseur_id, grcs_id, quantite_diesel, quantite_essence, date_dernier_approvisionnement, is_post_paye) SELECT id, grand_fournisseur_id, grcs_id, quantite_diesel, quantite_essence, date_dernier_approvisionnement, is_post_paye FROM __temp__ess_compte_grcs');
        $this->addSql('DROP TABLE __temp__ess_compte_grcs');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D16BA2C766151FA ON ess_compte_grcs (grand_fournisseur_id)');
        $this->addSql('CREATE INDEX IDX_1D16BA2CAB8F1F12 ON ess_compte_grcs (grcs_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ess_compte_petit_client AS SELECT id, petit_client_id, compte_grcs_id, quantite_diesel, quantite_essence, date_dernier_approvisionnement FROM ess_compte_petit_client');
        $this->addSql('DROP TABLE ess_compte_petit_client');
        $this->addSql('CREATE TABLE ess_compte_petit_client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, petit_client_id INTEGER NOT NULL, compte_grcs_id INTEGER NOT NULL, quantite_diesel INTEGER NOT NULL, quantite_essence INTEGER NOT NULL, date_dernier_approvisionnement DATETIME NOT NULL, CONSTRAINT FK_8AD12EACEF41F9BF FOREIGN KEY (petit_client_id) REFERENCES ess_petit_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_8AD12EAC13E82344 FOREIGN KEY (compte_grcs_id) REFERENCES ess_compte_grcs (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ess_compte_petit_client (id, petit_client_id, compte_grcs_id, quantite_diesel, quantite_essence, date_dernier_approvisionnement) SELECT id, petit_client_id, compte_grcs_id, quantite_diesel, quantite_essence, date_dernier_approvisionnement FROM __temp__ess_compte_petit_client');
        $this->addSql('DROP TABLE __temp__ess_compte_petit_client');
        $this->addSql('CREATE INDEX IDX_8AD12EACEF41F9BF ON ess_compte_petit_client (petit_client_id)');
        $this->addSql('CREATE INDEX IDX_8AD12EAC13E82344 ON ess_compte_petit_client (compte_grcs_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ess_grcs AS SELECT id, adresse_id, identite_id, entreprise_id, nombre_message, nombre_notification, prix_essence, prix_diesel, monnaie FROM ess_grcs');
        $this->addSql('DROP TABLE ess_grcs');
        $this->addSql('CREATE TABLE ess_grcs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, adresse_id INTEGER NOT NULL, identite_id INTEGER NOT NULL, entreprise_id INTEGER DEFAULT NULL, nombre_message INTEGER NOT NULL, nombre_notification INTEGER NOT NULL, prix_essence DOUBLE PRECISION NOT NULL, prix_diesel DOUBLE PRECISION NOT NULL, monnaie VARCHAR(255) NOT NULL, CONSTRAINT FK_6104F7134DE7DC5C FOREIGN KEY (adresse_id) REFERENCES ess_adresse (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6104F713E5F13C8F FOREIGN KEY (identite_id) REFERENCES ess_identite (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6104F713A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ess_grcs (id, adresse_id, identite_id, entreprise_id, nombre_message, nombre_notification, prix_essence, prix_diesel, monnaie) SELECT id, adresse_id, identite_id, entreprise_id, nombre_message, nombre_notification, prix_essence, prix_diesel, monnaie FROM __temp__ess_grcs');
        $this->addSql('DROP TABLE __temp__ess_grcs');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6104F7134DE7DC5C ON ess_grcs (adresse_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6104F713E5F13C8F ON ess_grcs (identite_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6104F713A4AEAFEA ON ess_grcs (entreprise_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ess_petit_client AS SELECT id, adresse_id, identite_id, entreprise_id, nombre_message, is_particulier FROM ess_petit_client');
        $this->addSql('DROP TABLE ess_petit_client');
        $this->addSql('CREATE TABLE ess_petit_client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, adresse_id INTEGER NOT NULL, identite_id INTEGER NOT NULL, entreprise_id INTEGER DEFAULT NULL, nombre_message INTEGER NOT NULL, is_particulier BOOLEAN NOT NULL, CONSTRAINT FK_81DCD59A4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES ess_adresse (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_81DCD59AE5F13C8F FOREIGN KEY (identite_id) REFERENCES ess_identite (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_81DCD59AA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ess_petit_client (id, adresse_id, identite_id, entreprise_id, nombre_message, is_particulier) SELECT id, adresse_id, identite_id, entreprise_id, nombre_message, is_particulier FROM __temp__ess_petit_client');
        $this->addSql('DROP TABLE __temp__ess_petit_client');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81DCD59A4DE7DC5C ON ess_petit_client (adresse_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81DCD59AE5F13C8F ON ess_petit_client (identite_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81DCD59AA4AEAFEA ON ess_petit_client (entreprise_id)');
    }
}
