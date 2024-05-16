<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240512173130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compte_courant (iduser_id INT DEFAULT NULL, ID INT AUTO_INCREMENT NOT NULL, cin INT NOT NULL, Prenom VARCHAR(255) NOT NULL, Status TINYINT(1) NOT NULL, Image VARCHAR(255) DEFAULT NULL, Montant DOUBLE PRECISION NOT NULL, INDEX IDX_73F05D6C786A81FB (iduser_id), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte_epargne (iduser_id INT DEFAULT NULL, ID INT AUTO_INCREMENT NOT NULL, cin INT NOT NULL, Prenom VARCHAR(255) NOT NULL, Status TINYINT(1) NOT NULL, Image VARCHAR(255) DEFAULT NULL, Montant DOUBLE PRECISION NOT NULL, INDEX IDX_19FDB51A786A81FB (iduser_id), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, iduser_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, revenu DOUBLE PRECISION NOT NULL, duree INT NOT NULL, date DATE DEFAULT NULL, statut VARCHAR(20) DEFAULT NULL, INDEX IDX_2694D7A5786A81FB (iduser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, userid_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, date_creation DATE DEFAULT NULL, description VARCHAR(500) DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, reponse VARCHAR(255) DEFAULT NULL, INDEX IDX_CE60640458E0A285 (userid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id_r INT AUTO_INCREMENT NOT NULL, montant_r DOUBLE PRECISION NOT NULL, duree_r INT NOT NULL, date_r DATE NOT NULL, statut_r VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id_r)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) DEFAULT NULL, prenom VARCHAR(30) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, date_creation DATE DEFAULT NULL, adresse VARCHAR(40) DEFAULT NULL, raison_sociale VARCHAR(30) DEFAULT NULL, telephone INT DEFAULT NULL, dateDeNaissance DATE DEFAULT NULL, statut VARCHAR(20) DEFAULT NULL, cin VARCHAR(30) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, role VARCHAR(20) DEFAULT NULL, banned VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE virement_international (ref INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(ref)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE compte_courant ADD CONSTRAINT FK_73F05D6C786A81FB FOREIGN KEY (iduser_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE compte_epargne ADD CONSTRAINT FK_19FDB51A786A81FB FOREIGN KEY (iduser_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5786A81FB FOREIGN KEY (iduser_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640458E0A285 FOREIGN KEY (userid_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compte_courant DROP FOREIGN KEY FK_73F05D6C786A81FB');
        $this->addSql('ALTER TABLE compte_epargne DROP FOREIGN KEY FK_19FDB51A786A81FB');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5786A81FB');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640458E0A285');
        $this->addSql('DROP TABLE compte_courant');
        $this->addSql('DROP TABLE compte_epargne');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE virement_international');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
