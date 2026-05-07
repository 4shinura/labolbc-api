<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260507112025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE profil (idProfil INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(100) DEFAULT NULL, typeProfil VARCHAR(50) DEFAULT NULL, PRIMARY KEY(idProfil)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visiteur (idVisiteur INT AUTO_INCREMENT NOT NULL, nomVisiteur VARCHAR(50) DEFAULT NULL, idProfil INT DEFAULT NULL, UNIQUE INDEX UNIQ_VISITEUR_IDPROFIL (idProfil), PRIMARY KEY(idVisiteur)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialite (numeroSequentiel INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(numeroSequentiel)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (numRegion INT AUTO_INCREMENT NOT NULL, libelleRegion VARCHAR(50) NOT NULL, PRIMARY KEY(numRegion)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ac (idAC INT AUTO_INCREMENT NOT NULL, themeAC VARCHAR(50) DEFAULT NULL, dateAC DATE DEFAULT NULL, lieuAC VARCHAR(50) DEFAULT NULL, PRIMARY KEY(idAC)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicament (idMedicament INT AUTO_INCREMENT NOT NULL, libelleMedicament VARCHAR(50) NOT NULL, PRIMARY KEY(idMedicament)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE praticien (numeroSequentiel INT NOT NULL, idPraticien INT NOT NULL, nomPraticien VARCHAR(50) DEFAULT NULL, prenomPraticien VARCHAR(50) DEFAULT NULL, PRIMARY KEY(numeroSequentiel, idPraticien)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visite (idVisite INT AUTO_INCREMENT NOT NULL, dateVisite DATE NOT NULL, motifVisite VARCHAR(200) DEFAULT NULL, bilanVisite VARCHAR(300) DEFAULT NULL, compteRenduVisite VARCHAR(100) DEFAULT NULL, idVisiteur INT NOT NULL, numeroSequentiel INT NOT NULL, idPraticien INT NOT NULL, PRIMARY KEY(idVisite)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE echantillon (id INT AUTO_INCREMENT NOT NULL, idVisite INT DEFAULT NULL, idMedicament INT DEFAULT NULL, quantite INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposer (idVisite INT NOT NULL, idMedicament INT NOT NULL, nb_echantillon INT DEFAULT NULL, PRIMARY KEY(idVisite, idMedicament)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repertorier (numeroSequentiel INT NOT NULL, idPraticien INT NOT NULL, idVisiteur INT NOT NULL, PRIMARY KEY(numeroSequentiel, idPraticien, idVisiteur)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE travailler (numeroSequentiel INT NOT NULL, idPraticien INT NOT NULL, numRegion INT NOT NULL, dateA DATE NOT NULL, PRIMARY KEY(numeroSequentiel, idPraticien, numRegion, dateA)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participer (numeroSequentiel INT NOT NULL, idPraticien INT NOT NULL, idAC INT NOT NULL, PRIMARY KEY(numeroSequentiel, idPraticien, idAC)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organiser (idVisiteur INT NOT NULL, idAC INT NOT NULL, PRIMARY KEY(idVisiteur, idAC)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE presenter (idVisiteur INT NOT NULL, numRegion INT NOT NULL, dateAffect DATE NOT NULL, PRIMARY KEY(idVisiteur, numRegion, dateAffect)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_VISITE_VISITEUR FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        $this->addSql('CREATE INDEX IDX_VISITE_ID_VISITEUR ON visite (idVisiteur)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_VISITE_PRATICIEN FOREIGN KEY (numeroSequentiel, idPraticien) REFERENCES praticien (numeroSequentiel, idPraticien)');
        $this->addSql('CREATE INDEX IDX_VISITE_PRATICIEN ON visite (numeroSequentiel, idPraticien)');

        $this->addSql('ALTER TABLE visiteur ADD CONSTRAINT FK_VISITEUR_PROFIL FOREIGN KEY (idProfil) REFERENCES profil (idProfil)');
        $this->addSql('ALTER TABLE praticien ADD CONSTRAINT FK_PRATICIEN_SPECIALITE FOREIGN KEY (numeroSequentiel) REFERENCES specialite (numeroSequentiel)');
        $this->addSql('ALTER TABLE echantillon ADD CONSTRAINT FK_ECHANTILLON_VISITE FOREIGN KEY (idVisite) REFERENCES visite (idVisite)');
        $this->addSql('CREATE INDEX IDX_ECHANTILLON_VISITE ON echantillon (idVisite)');
        $this->addSql('ALTER TABLE echantillon ADD CONSTRAINT FK_ECHANTILLON_MEDICAMENT FOREIGN KEY (idMedicament) REFERENCES medicament (idMedicament)');
        $this->addSql('CREATE INDEX IDX_ECHANTILLON_MEDICAMENT ON echantillon (idMedicament)');
        $this->addSql('ALTER TABLE proposer ADD CONSTRAINT FK_PROPOSER_VISITE FOREIGN KEY (idVisite) REFERENCES visite (idVisite)');
        $this->addSql('ALTER TABLE proposer ADD CONSTRAINT FK_PROPOSER_MEDICAMENT FOREIGN KEY (idMedicament) REFERENCES medicament (idMedicament)');
        $this->addSql('ALTER TABLE repertorier ADD CONSTRAINT FK_REPERTORIER_PRATICIEN FOREIGN KEY (numeroSequentiel, idPraticien) REFERENCES praticien (numeroSequentiel, idPraticien)');
        $this->addSql('ALTER TABLE repertorier ADD CONSTRAINT FK_REPERTORIER_VISITEUR FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        $this->addSql('ALTER TABLE travailler ADD CONSTRAINT FK_TRAVAILLER_PRATICIEN FOREIGN KEY (numeroSequentiel, idPraticien) REFERENCES praticien (numeroSequentiel, idPraticien)');
        $this->addSql('ALTER TABLE travailler ADD CONSTRAINT FK_TRAVAILLER_REGION FOREIGN KEY (numRegion) REFERENCES region (numRegion)');
        $this->addSql('ALTER TABLE participer ADD CONSTRAINT FK_PARTICIPER_PRATICIEN FOREIGN KEY (numeroSequentiel, idPraticien) REFERENCES praticien (numeroSequentiel, idPraticien)');
        $this->addSql('ALTER TABLE participer ADD CONSTRAINT FK_PARTICIPER_AC FOREIGN KEY (idAC) REFERENCES ac (idAC)');
        $this->addSql('ALTER TABLE organiser ADD CONSTRAINT FK_ORGANISER_VISITEUR FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        $this->addSql('ALTER TABLE organiser ADD CONSTRAINT FK_ORGANISER_AC FOREIGN KEY (idAC) REFERENCES ac (idAC)');
        $this->addSql('ALTER TABLE presenter ADD CONSTRAINT FK_PRESENTER_VISITEUR FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        $this->addSql('ALTER TABLE presenter ADD CONSTRAINT FK_PRESENTER_REGION FOREIGN KEY (numRegion) REFERENCES region (numRegion)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE presenter DROP FOREIGN KEY FK_PRESENTER_REGION');
        $this->addSql('ALTER TABLE presenter DROP FOREIGN KEY FK_PRESENTER_VISITEUR');
        $this->addSql('ALTER TABLE organiser DROP FOREIGN KEY FK_ORGANISER_AC');
        $this->addSql('ALTER TABLE organiser DROP FOREIGN KEY FK_ORGANISER_VISITEUR');
        $this->addSql('ALTER TABLE participer DROP FOREIGN KEY FK_PARTICIPER_AC');
        $this->addSql('ALTER TABLE participer DROP FOREIGN KEY FK_PARTICIPER_PRATICIEN');
        $this->addSql('ALTER TABLE travailler DROP FOREIGN KEY FK_TRAVAILLER_REGION');
        $this->addSql('ALTER TABLE travailler DROP FOREIGN KEY FK_TRAVAILLER_PRATICIEN');
        $this->addSql('ALTER TABLE repertorier DROP FOREIGN KEY FK_REPERTORIER_VISITEUR');
        $this->addSql('ALTER TABLE repertorier DROP FOREIGN KEY FK_REPERTORIER_PRATICIEN');
        $this->addSql('ALTER TABLE proposer DROP FOREIGN KEY FK_PROPOSER_MEDICAMENT');
        $this->addSql('ALTER TABLE proposer DROP FOREIGN KEY FK_PROPOSER_VISITE');
        $this->addSql('ALTER TABLE echantillon DROP FOREIGN KEY FK_ECHANTILLON_MEDICAMENT');
        $this->addSql('ALTER TABLE echantillon DROP FOREIGN KEY FK_ECHANTILLON_VISITE');
        $this->addSql('ALTER TABLE praticien DROP FOREIGN KEY FK_PRATICIEN_SPECIALITE');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_VISITE_PRATICIEN');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_VISITE_VISITEUR');
        $this->addSql('ALTER TABLE visiteur DROP FOREIGN KEY FK_VISITEUR_PROFIL');
        $this->addSql('DROP TABLE presenter');
        $this->addSql('DROP TABLE organiser');
        $this->addSql('DROP TABLE participer');
        $this->addSql('DROP TABLE travailler');
        $this->addSql('DROP TABLE repertorier');
        $this->addSql('DROP TABLE proposer');
        $this->addSql('DROP TABLE echantillon');
        $this->addSql('DROP TABLE visite');
        $this->addSql('DROP TABLE praticien');
        $this->addSql('DROP TABLE medicament');
        $this->addSql('DROP TABLE ac');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE specialite');
        $this->addSql('DROP TABLE visiteur');
        $this->addSql('DROP TABLE profil');
    }
}
