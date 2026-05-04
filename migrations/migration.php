<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260504093654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ac (idAC INT AUTO_INCREMENT NOT NULL, themeAC VARCHAR(50) DEFAULT NULL, dateAC DATE DEFAULT NULL, lieuAC VARCHAR(50) DEFAULT NULL, PRIMARY KEY (idAC)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE echantillon (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, idVisite INT DEFAULT NULL, idMedicament INT DEFAULT NULL, INDEX IDX_2C649BE7D38D2421 (idVisite), INDEX IDX_2C649BE718686CD0 (idMedicament), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE medicament (idMedicament INT AUTO_INCREMENT NOT NULL, libelleMedicament VARCHAR(50) NOT NULL, PRIMARY KEY (idMedicament)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE organiser (idVisiteur INT NOT NULL, idAC INT NOT NULL, INDEX IDX_96054AFC1D06ADE3 (idVisiteur), INDEX IDX_96054AFC2E036A07 (idAC), PRIMARY KEY (idVisiteur, idAC)) DEFAULT CHARACTER SET utf8mb4');
        
        // Correction : Table praticien avec clé primaire composite
        $this->addSql('CREATE TABLE praticien (numeroSequentiel INT NOT NULL, idPraticien INT NOT NULL, nomPraticien VARCHAR(50) NOT NULL, prenomPraticien VARCHAR(50) NOT NULL, PRIMARY KEY (numeroSequentiel, idPraticien)) DEFAULT CHARACTER SET utf8mb4');
        
        // Table participer avec clé étrangère composite vers praticien
        $this->addSql('CREATE TABLE participer (numeroSequentiel INT NOT NULL, idPraticien INT NOT NULL, idAC INT NOT NULL, INDEX IDX_EDBE16F82E036A07 (idAC), PRIMARY KEY (numeroSequentiel, idPraticien, idAC)) DEFAULT CHARACTER SET utf8mb4');
        
        $this->addSql('CREATE TABLE presenter (idVisiteur INT NOT NULL, numRegion INT NOT NULL, dateAffect VARCHAR(10) NOT NULL, INDEX IDX_D3B56AC81D06ADE3 (idVisiteur), INDEX IDX_D3B56AC8C575A9AA (numRegion), PRIMARY KEY (idVisiteur, numRegion, dateAffect)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE profil (idProfil INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, typeProfil VARCHAR(50) NOT NULL, PRIMARY KEY (idProfil)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE proposer (idVisite INT NOT NULL, idMedicament INT NOT NULL, nb_echantillon INT NOT NULL, INDEX IDX_21866C15D38D2421 (idVisite), INDEX IDX_21866C1518686CD0 (idMedicament), PRIMARY KEY (idVisite, idMedicament)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE region (numRegion INT AUTO_INCREMENT NOT NULL, libelleRegion VARCHAR(50) NOT NULL, PRIMARY KEY (numRegion)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE repertorier (numeroSequentiel INT NOT NULL, idPraticien INT NOT NULL, idVisiteur INT NOT NULL, PRIMARY KEY (numeroSequentiel, idPraticien, idVisiteur)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE specialite (numeroSequentiel INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY (numeroSequentiel)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE travailler (numeroSequentiel INT NOT NULL, idPraticien INT NOT NULL, numRegion INT NOT NULL, dateA VARCHAR(10) NOT NULL, INDEX IDX_90B2DF3DC575A9AA (numRegion), PRIMARY KEY (numeroSequentiel, idPraticien, numRegion, dateA)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE visite (idVisite INT AUTO_INCREMENT NOT NULL, dateVisite DATE NOT NULL, motifVisite VARCHAR(200) NOT NULL, bilanVisite VARCHAR(300) DEFAULT NULL, compteRenduVisite VARCHAR(100) DEFAULT NULL, idVisiteur INT DEFAULT NULL, praticien_numeroSequentiel INT NOT NULL, praticien_idPraticien INT NOT NULL, INDEX IDX_B09C8CBB1D06ADE3 (idVisiteur), INDEX IDX_B09C8CBB_8C9D0F9E_9B2E8F6A (praticien_numeroSequentiel, praticien_idPraticien), PRIMARY KEY (idVisite)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE visiteur (idVisiteur INT AUTO_INCREMENT NOT NULL, nomVisiteur VARCHAR(50) NOT NULL, idProfil INT DEFAULT NULL, UNIQUE INDEX UNIQ_4EA587B885C71A0D (idProfil), PRIMARY KEY (idVisiteur)) DEFAULT CHARACTER SET utf8mb4');
        
        // Ajout des contraintes de clé étrangère
        $this->addSql('ALTER TABLE echantillon ADD CONSTRAINT FK_2C649BE7D38D2421 FOREIGN KEY (idVisite) REFERENCES visite (idVisite)');
        $this->addSql('ALTER TABLE echantillon ADD CONSTRAINT FK_2C649BE718686CD0 FOREIGN KEY (idMedicament) REFERENCES medicament (idMedicament)');
        $this->addSql('ALTER TABLE organiser ADD CONSTRAINT FK_96054AFC1D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        $this->addSql('ALTER TABLE organiser ADD CONSTRAINT FK_96054AFC2E036A07 FOREIGN KEY (idAC) REFERENCES ac (idAC)');
        
        // Clé étrangère composite pour participer
        $this->addSql('ALTER TABLE participer ADD CONSTRAINT FK_EDBE16F8F8960AE6F1700C85 FOREIGN KEY (numeroSequentiel, idPraticien) REFERENCES praticien (numeroSequentiel, idPraticien)');
        $this->addSql('ALTER TABLE participer ADD CONSTRAINT FK_EDBE16F82E036A07 FOREIGN KEY (idAC) REFERENCES ac (idAC)');
        
        // Clé étrangère pour praticien vers specialite
        $this->addSql('ALTER TABLE praticien ADD CONSTRAINT FK_D9A27D3F8960AE6 FOREIGN KEY (numeroSequentiel) REFERENCES specialite (numeroSequentiel)');
        
        $this->addSql('ALTER TABLE presenter ADD CONSTRAINT FK_D3B56AC81D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        $this->addSql('ALTER TABLE presenter ADD CONSTRAINT FK_D3B56AC8C575A9AA FOREIGN KEY (numRegion) REFERENCES region (numRegion)');
        $this->addSql('ALTER TABLE proposer ADD CONSTRAINT FK_21866C15D38D2421 FOREIGN KEY (idVisite) REFERENCES visite (idVisite)');
        $this->addSql('ALTER TABLE proposer ADD CONSTRAINT FK_21866C1518686CD0 FOREIGN KEY (idMedicament) REFERENCES medicament (idMedicament)');
        
        // Clé étrangère composite pour repertorier
        $this->addSql('ALTER TABLE repertorier ADD CONSTRAINT FK_423881E3F8960AE6F1700C85 FOREIGN KEY (numeroSequentiel, idPraticien) REFERENCES praticien (numeroSequentiel, idPraticien)');
        $this->addSql('ALTER TABLE repertorier ADD CONSTRAINT FK_423881E31D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        
        // Clé étrangère composite pour travailler
        $this->addSql('ALTER TABLE travailler ADD CONSTRAINT FK_90B2DF3DF8960AE6F1700C85 FOREIGN KEY (numeroSequentiel, idPraticien) REFERENCES praticien (numeroSequentiel, idPraticien)');
        $this->addSql('ALTER TABLE travailler ADD CONSTRAINT FK_90B2DF3DC575A9AA FOREIGN KEY (numRegion) REFERENCES region (numRegion)');
        
        // Clé étrangère composite pour visite
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBB1D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBB_8C9D0F9E_9B2E8F6A FOREIGN KEY (praticien_numeroSequentiel, praticien_idPraticien) REFERENCES praticien (numeroSequentiel, idPraticien)');
        
        $this->addSql('ALTER TABLE visiteur ADD CONSTRAINT FK_4EA587B885C71A0D FOREIGN KEY (idProfil) REFERENCES profil (idProfil)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE echantillon DROP FOREIGN KEY FK_2C649BE7D38D2421');
        $this->addSql('ALTER TABLE echantillon DROP FOREIGN KEY FK_2C649BE718686CD0');
        $this->addSql('ALTER TABLE organiser DROP FOREIGN KEY FK_96054AFC1D06ADE3');
        $this->addSql('ALTER TABLE organiser DROP FOREIGN KEY FK_96054AFC2E036A07');
        $this->addSql('ALTER TABLE participer DROP FOREIGN KEY FK_EDBE16F8F8960AE6F1700C85');
        $this->addSql('ALTER TABLE participer DROP FOREIGN KEY FK_EDBE16F82E036A07');
        $this->addSql('ALTER TABLE praticien DROP FOREIGN KEY FK_D9A27D3F8960AE6');
        $this->addSql('ALTER TABLE presenter DROP FOREIGN KEY FK_D3B56AC81D06ADE3');
        $this->addSql('ALTER TABLE presenter DROP FOREIGN KEY FK_D3B56AC8C575A9AA');
        $this->addSql('ALTER TABLE proposer DROP FOREIGN KEY FK_21866C15D38D2421');
        $this->addSql('ALTER TABLE proposer DROP FOREIGN KEY FK_21866C1518686CD0');
        $this->addSql('ALTER TABLE repertorier DROP FOREIGN KEY FK_423881E3F8960AE6F1700C85');
        $this->addSql('ALTER TABLE repertorier DROP FOREIGN KEY FK_423881E31D06ADE3');
        $this->addSql('ALTER TABLE travailler DROP FOREIGN KEY FK_90B2DF3DF8960AE6F1700C85');
        $this->addSql('ALTER TABLE travailler DROP FOREIGN KEY FK_90B2DF3DC575A9AA');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBB1D06ADE3');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBB_8C9D0F9E_9B2E8F6A');
        $this->addSql('ALTER TABLE visiteur DROP FOREIGN KEY FK_4EA587B885C71A0D');
        $this->addSql('DROP TABLE ac');
        $this->addSql('DROP TABLE echantillon');
        $this->addSql('DROP TABLE medicament');
        $this->addSql('DROP TABLE organiser');
        $this->addSql('DROP TABLE participer');
        $this->addSql('DROP TABLE praticien');
        $this->addSql('DROP TABLE presenter');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE proposer');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE repertorier');
        $this->addSql('DROP TABLE specialite');
        $this->addSql('DROP TABLE travailler');
        $this->addSql('DROP TABLE visite');
        $this->addSql('DROP TABLE visiteur');
    }
}