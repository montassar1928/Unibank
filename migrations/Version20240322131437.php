<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240322131437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY fk_refOperation');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY id_user');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY id_reclamation');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE demande_credit');
        $this->addSql('DROP TABLE jointureoperationcompte');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reponse_demande');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE virement_international');
        $this->addSql('ALTER TABLE users CHANGE nom nom VARCHAR(30) DEFAULT NULL, CHANGE prenom prenom VARCHAR(30) DEFAULT NULL, CHANGE email email VARCHAR(50) DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE date_creation date_creation DATE DEFAULT NULL, CHANGE adresse adresse VARCHAR(40) DEFAULT NULL, CHANGE Raison_Sociale raison_sociale VARCHAR(30) DEFAULT NULL, CHANGE dateDeNaissance dateDeNaissance DATE DEFAULT NULL, CHANGE statut statut VARCHAR(20) DEFAULT NULL, CHANGE cin cin VARCHAR(30) DEFAULT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL, CHANGE Role role VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compte (numCompte INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, montant DOUBLE PRECISION NOT NULL, PRIMARY KEY(numCompte)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE demande_credit (id_demande INT NOT NULL, id_client INT DEFAULT NULL, Montant_Souhaite DOUBLE PRECISION NOT NULL, Duree INT NOT NULL, Revenue_Annuel DOUBLE PRECISION NOT NULL, Score_Credit INT DEFAULT NULL, Date_Demande DATE NOT NULL, Statut VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE jointureoperationcompte (numOperation INT NOT NULL, numCompte INT NOT NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE operation (ref INT NOT NULL, numOperation INT AUTO_INCREMENT NOT NULL, typeOperation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, dateOperation DATETIME DEFAULT \'current_timestamp()\' NOT NULL, description VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, statusOperation TINYINT(1) NOT NULL, montantOpt DOUBLE PRECISION NOT NULL, Emetteur VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, Recepteur VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, INDEX fk_refOperation (ref), PRIMARY KEY(numOperation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reclamation (id_reclamation INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, message TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, statut VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, nom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, date_rec DATE DEFAULT \'NULL\', INDEX id_user (id_user), PRIMARY KEY(id_reclamation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reponse (id_reponse INT AUTO_INCREMENT NOT NULL, id_reclamation INT DEFAULT NULL, message_rep VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, date_rep DATETIME DEFAULT \'NULL\', statut VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, nomUser VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, typeReclamation VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, INDEX id_reclamation (id_reclamation), PRIMARY KEY(id_reponse)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reponse_demande (id_reponse INT NOT NULL, id_demande INT DEFAULT NULL, Montant_Approuve DOUBLE PRECISION NOT NULL, Taux_Interet DOUBLE PRECISION NOT NULL, Duree_Approuvee INT NOT NULL, Date_Reponse DATE NOT NULL, Commentaire VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, statut VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, telephone INT DEFAULT NULL, Description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, Statut VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, idclient INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE virement_international (ref INT AUTO_INCREMENT NOT NULL, taux_echange DOUBLE PRECISION NOT NULL, currency VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(ref)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT fk_refOperation FOREIGN KEY (ref) REFERENCES virement_international (ref)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT id_user FOREIGN KEY (id_user) REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT id_reclamation FOREIGN KEY (id_reclamation) REFERENCES reclamation (id_reclamation)');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE users CHANGE nom nom VARCHAR(30) DEFAULT \'NULL\', CHANGE prenom prenom VARCHAR(30) DEFAULT \'NULL\', CHANGE email email VARCHAR(50) DEFAULT \'NULL\', CHANGE password password VARCHAR(255) DEFAULT \'NULL\', CHANGE date_creation date_creation DATE DEFAULT \'NULL\', CHANGE adresse adresse VARCHAR(40) DEFAULT \'NULL\', CHANGE raison_sociale Raison_Sociale VARCHAR(30) DEFAULT \'NULL\', CHANGE dateDeNaissance dateDeNaissance DATE DEFAULT \'NULL\', CHANGE statut statut VARCHAR(20) DEFAULT \'NULL\', CHANGE cin cin VARCHAR(30) DEFAULT \'NULL\', CHANGE photo photo VARCHAR(255) DEFAULT \'NULL\', CHANGE role Role VARCHAR(255) DEFAULT \'NULL\'');
    }
}
