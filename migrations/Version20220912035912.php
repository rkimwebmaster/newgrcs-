<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220912035912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activation_post_paye_grcs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, compte_grcs_id INTEGER NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, nombre_jour VARCHAR(255) NOT NULL --(DC2Type:dateinterval)
        , is_cloture BOOLEAN NOT NULL, quantite_max_diesel_autorise INTEGER NOT NULL, quantite_max_essence_autorise INTEGER NOT NULL, CONSTRAINT FK_D64C9F9A13E82344 FOREIGN KEY (compte_grcs_id) REFERENCES ess_compte_grcs (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D64C9F9A13E82344 ON activation_post_paye_grcs (compte_grcs_id)');
        $this->addSql('CREATE TABLE entreprise (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, sigle VARCHAR(255) NOT NULL, rccm VARCHAR(255) DEFAULT NULL, idnat VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE ess_activation_post_paye (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, compte_petit_client_id INTEGER NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, nombre_jour VARCHAR(255) NOT NULL --(DC2Type:dateinterval)
        , is_cloture BOOLEAN NOT NULL, quantite_max_diesel_autorise INTEGER DEFAULT NULL, quantite_max_essence_autorise INTEGER DEFAULT NULL, CONSTRAINT FK_62CF9FED268DB0EF FOREIGN KEY (compte_petit_client_id) REFERENCES ess_compte_petit_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_62CF9FED268DB0EF ON ess_activation_post_paye (compte_petit_client_id)');
        $this->addSql('CREATE TABLE ess_adresse (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE ess_approvisionnement_grcs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, compte_grcs_id INTEGER DEFAULT NULL, utilisateur_id INTEGER DEFAULT NULL, date DATETIME NOT NULL, is_confirme BOOLEAN NOT NULL, is_diesel BOOLEAN NOT NULL, quantite_essence INTEGER NOT NULL, quantite_diesel INTEGER NOT NULL, CONSTRAINT FK_10F6CFCF13E82344 FOREIGN KEY (compte_grcs_id) REFERENCES ess_compte_grcs (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_10F6CFCFFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES "ess_user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_10F6CFCF13E82344 ON ess_approvisionnement_grcs (compte_grcs_id)');
        $this->addSql('CREATE INDEX IDX_10F6CFCFFB88E14F ON ess_approvisionnement_grcs (utilisateur_id)');
        $this->addSql('CREATE TABLE ess_approvisionnement_petit_client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER DEFAULT NULL, compte_petit_client_id INTEGER DEFAULT NULL, date DATETIME NOT NULL, is_diesel BOOLEAN NOT NULL, montant DOUBLE PRECISION NOT NULL, bordereau VARCHAR(255) NOT NULL, numero_bordereau VARCHAR(255) NOT NULL, is_confirme BOOLEAN NOT NULL, quantite_essence INTEGER NOT NULL, quantite_diesel INTEGER NOT NULL, CONSTRAINT FK_D70A2C91FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES "ess_user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D70A2C91268DB0EF FOREIGN KEY (compte_petit_client_id) REFERENCES ess_compte_petit_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D70A2C91FB88E14F ON ess_approvisionnement_petit_client (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_D70A2C91268DB0EF ON ess_approvisionnement_petit_client (compte_petit_client_id)');
        $this->addSql('CREATE TABLE ess_compte_grcs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, grand_fournisseur_id INTEGER NOT NULL, grcs_id INTEGER NOT NULL, quantite_diesel INTEGER NOT NULL, quantite_essence INTEGER NOT NULL, date_dernier_approvisionnement DATETIME NOT NULL, is_post_paye BOOLEAN DEFAULT NULL, CONSTRAINT FK_1D16BA2C766151FA FOREIGN KEY (grand_fournisseur_id) REFERENCES ess_grand_fournisseur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1D16BA2CAB8F1F12 FOREIGN KEY (grcs_id) REFERENCES ess_grcs (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D16BA2C766151FA ON ess_compte_grcs (grand_fournisseur_id)');
        $this->addSql('CREATE INDEX IDX_1D16BA2CAB8F1F12 ON ess_compte_grcs (grcs_id)');
        $this->addSql('CREATE TABLE ess_compte_petit_client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, petit_client_id INTEGER NOT NULL, compte_grcs_id INTEGER NOT NULL, quantite_diesel INTEGER NOT NULL, quantite_essence INTEGER NOT NULL, date_dernier_approvisionnement DATETIME NOT NULL, CONSTRAINT FK_8AD12EACEF41F9BF FOREIGN KEY (petit_client_id) REFERENCES ess_petit_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_8AD12EAC13E82344 FOREIGN KEY (compte_grcs_id) REFERENCES ess_compte_grcs (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_8AD12EACEF41F9BF ON ess_compte_petit_client (petit_client_id)');
        $this->addSql('CREATE INDEX IDX_8AD12EAC13E82344 ON ess_compte_petit_client (compte_grcs_id)');
        $this->addSql('CREATE TABLE ess_grand_fournisseur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, adresse_id INTEGER NOT NULL, identite_id INTEGER NOT NULL, entreprise_id INTEGER DEFAULT NULL, nombre_message INTEGER NOT NULL, nombre_notification INTEGER DEFAULT NULL, CONSTRAINT FK_1C22640C4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES ess_adresse (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1C22640CE5F13C8F FOREIGN KEY (identite_id) REFERENCES ess_identite (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1C22640CA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1C22640C4DE7DC5C ON ess_grand_fournisseur (adresse_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1C22640CE5F13C8F ON ess_grand_fournisseur (identite_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1C22640CA4AEAFEA ON ess_grand_fournisseur (entreprise_id)');
        $this->addSql('CREATE TABLE ess_grcs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, adresse_id INTEGER NOT NULL, identite_id INTEGER NOT NULL, entreprise_id INTEGER DEFAULT NULL, nombre_message INTEGER NOT NULL, nombre_notification INTEGER NOT NULL, prix_essence DOUBLE PRECISION NOT NULL, prix_diesel DOUBLE PRECISION NOT NULL, monnaie VARCHAR(255) NOT NULL, CONSTRAINT FK_6104F7134DE7DC5C FOREIGN KEY (adresse_id) REFERENCES ess_adresse (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6104F713E5F13C8F FOREIGN KEY (identite_id) REFERENCES ess_identite (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6104F713A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6104F7134DE7DC5C ON ess_grcs (adresse_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6104F713E5F13C8F ON ess_grcs (identite_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6104F713A4AEAFEA ON ess_grcs (entreprise_id)');
        $this->addSql('CREATE TABLE ess_identite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, postnom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE ess_message_grand_fournisseur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER DEFAULT NULL, date DATETIME NOT NULL, contenu CLOB NOT NULL, sujet VARCHAR(255) NOT NULL, CONSTRAINT FK_497E35E5FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES "ess_user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_497E35E5FB88E14F ON ess_message_grand_fournisseur (utilisateur_id)');
        $this->addSql('CREATE TABLE message_grand_fournisseur_grand_fournisseur (message_grand_fournisseur_id INTEGER NOT NULL, grand_fournisseur_id INTEGER NOT NULL, PRIMARY KEY(message_grand_fournisseur_id, grand_fournisseur_id), CONSTRAINT FK_CB124EEA1E4C6A57 FOREIGN KEY (message_grand_fournisseur_id) REFERENCES ess_message_grand_fournisseur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CB124EEA766151FA FOREIGN KEY (grand_fournisseur_id) REFERENCES ess_grand_fournisseur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CB124EEA1E4C6A57 ON message_grand_fournisseur_grand_fournisseur (message_grand_fournisseur_id)');
        $this->addSql('CREATE INDEX IDX_CB124EEA766151FA ON message_grand_fournisseur_grand_fournisseur (grand_fournisseur_id)');
        $this->addSql('CREATE TABLE ess_message_grcs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, grcs_id INTEGER NOT NULL, utilisateur_id INTEGER DEFAULT NULL, date DATETIME NOT NULL, contenu CLOB NOT NULL, sujet VARCHAR(255) NOT NULL, CONSTRAINT FK_852A3607AB8F1F12 FOREIGN KEY (grcs_id) REFERENCES ess_grcs (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_852A3607FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES "ess_user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_852A3607AB8F1F12 ON ess_message_grcs (grcs_id)');
        $this->addSql('CREATE INDEX IDX_852A3607FB88E14F ON ess_message_grcs (utilisateur_id)');
        $this->addSql('CREATE TABLE ess_message_petit_client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATETIME NOT NULL, contenu CLOB NOT NULL, sujet VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE message_petit_client_petit_client (message_petit_client_id INTEGER NOT NULL, petit_client_id INTEGER NOT NULL, PRIMARY KEY(message_petit_client_id, petit_client_id), CONSTRAINT FK_508D70679718A749 FOREIGN KEY (message_petit_client_id) REFERENCES ess_message_petit_client (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_508D7067EF41F9BF FOREIGN KEY (petit_client_id) REFERENCES ess_petit_client (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_508D70679718A749 ON message_petit_client_petit_client (message_petit_client_id)');
        $this->addSql('CREATE INDEX IDX_508D7067EF41F9BF ON message_petit_client_petit_client (petit_client_id)');
        $this->addSql('CREATE TABLE ess_petit_client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, adresse_id INTEGER NOT NULL, identite_id INTEGER NOT NULL, entreprise_id INTEGER DEFAULT NULL, nombre_message INTEGER NOT NULL, is_particulier BOOLEAN NOT NULL, CONSTRAINT FK_81DCD59A4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES ess_adresse (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_81DCD59AE5F13C8F FOREIGN KEY (identite_id) REFERENCES ess_identite (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_81DCD59AA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81DCD59A4DE7DC5C ON ess_petit_client (adresse_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81DCD59AE5F13C8F ON ess_petit_client (identite_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81DCD59AA4AEAFEA ON ess_petit_client (entreprise_id)');
        $this->addSql('CREATE TABLE ess_ticket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, compte_petit_client_id INTEGER NOT NULL, date DATETIME NOT NULL, nom_chauffeur VARCHAR(255) DEFAULT NULL, numero_plaque_immatriculation VARCHAR(255) DEFAULT NULL, is_groupe_electrogene BOOLEAN NOT NULL, date_retrait DATETIME NOT NULL, quantite INTEGER NOT NULL, is_credit BOOLEAN NOT NULL, total_montant DOUBLE PRECISION NOT NULL, is_servi BOOLEAN NOT NULL, type_carburant VARCHAR(255) NOT NULL, CONSTRAINT FK_CC4A5B7FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES "ess_user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CC4A5B7F268DB0EF FOREIGN KEY (compte_petit_client_id) REFERENCES ess_compte_petit_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CC4A5B7FFB88E14F ON ess_ticket (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_CC4A5B7F268DB0EF ON ess_ticket (compte_petit_client_id)');
        $this->addSql('CREATE TABLE "ess_user" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, grcs_id INTEGER DEFAULT NULL, grand_fournisseur_id INTEGER DEFAULT NULL, petit_client_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_BB8B9520AB8F1F12 FOREIGN KEY (grcs_id) REFERENCES ess_grcs (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BB8B9520766151FA FOREIGN KEY (grand_fournisseur_id) REFERENCES ess_grand_fournisseur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BB8B9520EF41F9BF FOREIGN KEY (petit_client_id) REFERENCES ess_petit_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BB8B9520E7927C74 ON "ess_user" (email)');
        $this->addSql('CREATE INDEX IDX_BB8B9520AB8F1F12 ON "ess_user" (grcs_id)');
        $this->addSql('CREATE INDEX IDX_BB8B9520766151FA ON "ess_user" (grand_fournisseur_id)');
        $this->addSql('CREATE INDEX IDX_BB8B9520EF41F9BF ON "ess_user" (petit_client_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE activation_post_paye_grcs');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE ess_activation_post_paye');
        $this->addSql('DROP TABLE ess_adresse');
        $this->addSql('DROP TABLE ess_approvisionnement_grcs');
        $this->addSql('DROP TABLE ess_approvisionnement_petit_client');
        $this->addSql('DROP TABLE ess_compte_grcs');
        $this->addSql('DROP TABLE ess_compte_petit_client');
        $this->addSql('DROP TABLE ess_grand_fournisseur');
        $this->addSql('DROP TABLE ess_grcs');
        $this->addSql('DROP TABLE ess_identite');
        $this->addSql('DROP TABLE ess_message_grand_fournisseur');
        $this->addSql('DROP TABLE message_grand_fournisseur_grand_fournisseur');
        $this->addSql('DROP TABLE ess_message_grcs');
        $this->addSql('DROP TABLE ess_message_petit_client');
        $this->addSql('DROP TABLE message_petit_client_petit_client');
        $this->addSql('DROP TABLE ess_petit_client');
        $this->addSql('DROP TABLE ess_ticket');
        $this->addSql('DROP TABLE "ess_user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
