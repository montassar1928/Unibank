<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513145115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compte_courant CHANGE Image Image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE compte_epargne CHANGE Image Image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE demande CHANGE date date DATE DEFAULT NULL, CHANGE statut statut VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D146F3EA3 FOREIGN KEY (ref) REFERENCES virement_international (ref)');
        $this->addSql('ALTER TABLE reclamation CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE date_creation date_creation DATE DEFAULT NULL, CHANGE description description VARCHAR(500) DEFAULT NULL, CHANGE etat etat VARCHAR(255) DEFAULT NULL, CHANGE reponse reponse VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse CHANGE statut_r statut_r VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE nom nom VARCHAR(30) DEFAULT NULL, CHANGE prenom prenom VARCHAR(30) DEFAULT NULL, CHANGE email email VARCHAR(50) DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE date_creation date_creation DATE DEFAULT NULL, CHANGE adresse adresse VARCHAR(40) DEFAULT NULL, CHANGE raison_sociale raison_sociale VARCHAR(30) DEFAULT NULL, CHANGE dateDeNaissance dateDeNaissance DATE DEFAULT NULL, CHANGE statut statut VARCHAR(20) DEFAULT NULL, CHANGE cin cin VARCHAR(30) DEFAULT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL, CHANGE role role VARCHAR(20) DEFAULT NULL, CHANGE banned banned VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compte_courant CHANGE Image Image VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE compte_epargne CHANGE Image Image VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE demande CHANGE date date DATE DEFAULT \'NULL\', CHANGE statut statut VARCHAR(20) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D146F3EA3');
        $this->addSql('ALTER TABLE reclamation CHANGE title title VARCHAR(255) DEFAULT \'NULL\', CHANGE date_creation date_creation DATE DEFAULT \'NULL\', CHANGE description description VARCHAR(500) DEFAULT \'NULL\', CHANGE etat etat VARCHAR(255) DEFAULT \'NULL\', CHANGE reponse reponse VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE reponse CHANGE statut_r statut_r VARCHAR(20) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE users CHANGE nom nom VARCHAR(30) DEFAULT \'NULL\', CHANGE prenom prenom VARCHAR(30) DEFAULT \'NULL\', CHANGE email email VARCHAR(50) DEFAULT \'NULL\', CHANGE password password VARCHAR(255) DEFAULT \'NULL\', CHANGE date_creation date_creation DATE DEFAULT \'NULL\', CHANGE adresse adresse VARCHAR(40) DEFAULT \'NULL\', CHANGE raison_sociale raison_sociale VARCHAR(30) DEFAULT \'NULL\', CHANGE dateDeNaissance dateDeNaissance DATE DEFAULT \'NULL\', CHANGE statut statut VARCHAR(20) DEFAULT \'NULL\', CHANGE cin cin VARCHAR(30) DEFAULT \'NULL\', CHANGE photo photo VARCHAR(255) DEFAULT \'NULL\', CHANGE role role VARCHAR(20) DEFAULT \'NULL\', CHANGE banned banned VARCHAR(255) DEFAULT \'NULL\'');
    }
}
