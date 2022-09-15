<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220915085626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ess_message ADD COLUMN is_lu BOOLEAN DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__ess_message AS SELECT id, expediteur_user_id, destinataire_grcs_id, destinataire_fournisseur_id, destinataire_client_id, date, expediteur_structure, sujet, contenu FROM ess_message');
        $this->addSql('DROP TABLE ess_message');
        $this->addSql('CREATE TABLE ess_message (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, expediteur_user_id INTEGER DEFAULT NULL, destinataire_grcs_id INTEGER DEFAULT NULL, destinataire_fournisseur_id INTEGER DEFAULT NULL, destinataire_client_id INTEGER DEFAULT NULL, date DATETIME NOT NULL, expediteur_structure VARCHAR(255) DEFAULT NULL, sujet VARCHAR(255) NOT NULL, contenu CLOB NOT NULL, CONSTRAINT FK_398344762AB8B71D FOREIGN KEY (expediteur_user_id) REFERENCES "ess_user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_39834476E943A9C9 FOREIGN KEY (destinataire_grcs_id) REFERENCES ess_grcs (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_398344761FA347C4 FOREIGN KEY (destinataire_fournisseur_id) REFERENCES ess_grand_fournisseur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3983447659252180 FOREIGN KEY (destinataire_client_id) REFERENCES ess_petit_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ess_message (id, expediteur_user_id, destinataire_grcs_id, destinataire_fournisseur_id, destinataire_client_id, date, expediteur_structure, sujet, contenu) SELECT id, expediteur_user_id, destinataire_grcs_id, destinataire_fournisseur_id, destinataire_client_id, date, expediteur_structure, sujet, contenu FROM __temp__ess_message');
        $this->addSql('DROP TABLE __temp__ess_message');
        $this->addSql('CREATE INDEX IDX_398344762AB8B71D ON ess_message (expediteur_user_id)');
        $this->addSql('CREATE INDEX IDX_39834476E943A9C9 ON ess_message (destinataire_grcs_id)');
        $this->addSql('CREATE INDEX IDX_398344761FA347C4 ON ess_message (destinataire_fournisseur_id)');
        $this->addSql('CREATE INDEX IDX_3983447659252180 ON ess_message (destinataire_client_id)');
    }
}
