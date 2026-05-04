<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504125253 extends AbstractMigration
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
        $this->addSql('CREATE TABLE participer (id INT AUTO_INCREMENT NOT NULL, praticien_id INT NOT NULL, ac_id INT NOT NULL, INDEX IDX_EDBE16F82391866B (praticien_id), INDEX IDX_EDBE16F8D2E3ED2F (ac_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE praticien (id INT AUTO_INCREMENT NOT NULL, numeroSequentiel INT NOT NULL, idPraticien INT NOT NULL, nomPraticien VARCHAR(50) NOT NULL, prenomPraticien VARCHAR(50) NOT NULL, specialite_id INT DEFAULT NULL, INDEX IDX_D9A27D32195E0F0 (specialite_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE presenter (idVisiteur INT NOT NULL, numRegion INT NOT NULL, dateAffect VARCHAR(10) NOT NULL, INDEX IDX_D3B56AC81D06ADE3 (idVisiteur), INDEX IDX_D3B56AC8C575A9AA (numRegion), PRIMARY KEY (idVisiteur, numRegion, dateAffect)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE profil (idProfil INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, typeProfil VARCHAR(50) NOT NULL, PRIMARY KEY (idProfil)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE proposer (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, visite_id INT NOT NULL, medicament_id INT NOT NULL, INDEX IDX_21866C15C1C5DC59 (visite_id), INDEX IDX_21866C15AB0D61F7 (medicament_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE region (numRegion INT AUTO_INCREMENT NOT NULL, libelleRegion VARCHAR(50) NOT NULL, PRIMARY KEY (numRegion)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE repertorier (id INT AUTO_INCREMENT NOT NULL, praticien_id INT NOT NULL, visiteur_id INT NOT NULL, INDEX IDX_423881E32391866B (praticien_id), INDEX IDX_423881E37F72333D (visiteur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE specialite (numeroSequentiel INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY (numeroSequentiel)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE travailler (id INT AUTO_INCREMENT NOT NULL, dateA VARCHAR(10) NOT NULL, praticien_id INT NOT NULL, region_id INT NOT NULL, INDEX IDX_90B2DF3D2391866B (praticien_id), INDEX IDX_90B2DF3D98260155 (region_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE visite (idVisite INT AUTO_INCREMENT NOT NULL, dateVisite DATE NOT NULL, motifVisite VARCHAR(200) NOT NULL, bilanVisite VARCHAR(300) DEFAULT NULL, compteRenduVisite VARCHAR(100) DEFAULT NULL, idVisiteur INT DEFAULT NULL, praticien_id INT NOT NULL, INDEX IDX_B09C8CBB1D06ADE3 (idVisiteur), INDEX IDX_B09C8CBB2391866B (praticien_id), PRIMARY KEY (idVisite)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE visiteur (idVisiteur INT AUTO_INCREMENT NOT NULL, nomVisiteur VARCHAR(50) NOT NULL, idProfil INT DEFAULT NULL, UNIQUE INDEX UNIQ_4EA587B885C71A0D (idProfil), PRIMARY KEY (idVisiteur)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE echantillon ADD CONSTRAINT FK_2C649BE7D38D2421 FOREIGN KEY (idVisite) REFERENCES visite (idVisite)');
        $this->addSql('ALTER TABLE echantillon ADD CONSTRAINT FK_2C649BE718686CD0 FOREIGN KEY (idMedicament) REFERENCES medicament (idMedicament)');
        $this->addSql('ALTER TABLE organiser ADD CONSTRAINT FK_96054AFC1D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        $this->addSql('ALTER TABLE organiser ADD CONSTRAINT FK_96054AFC2E036A07 FOREIGN KEY (idAC) REFERENCES ac (idAC)');
        $this->addSql('ALTER TABLE participer ADD CONSTRAINT FK_EDBE16F82391866B FOREIGN KEY (praticien_id) REFERENCES praticien (id)');
        $this->addSql('ALTER TABLE participer ADD CONSTRAINT FK_EDBE16F8D2E3ED2F FOREIGN KEY (ac_id) REFERENCES ac (idAC)');
        $this->addSql('ALTER TABLE praticien ADD CONSTRAINT FK_D9A27D32195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (numeroSequentiel)');
        $this->addSql('ALTER TABLE presenter ADD CONSTRAINT FK_D3B56AC81D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        $this->addSql('ALTER TABLE presenter ADD CONSTRAINT FK_D3B56AC8C575A9AA FOREIGN KEY (numRegion) REFERENCES region (numRegion)');
        $this->addSql('ALTER TABLE proposer ADD CONSTRAINT FK_21866C15C1C5DC59 FOREIGN KEY (visite_id) REFERENCES visite (idVisite)');
        $this->addSql('ALTER TABLE proposer ADD CONSTRAINT FK_21866C15AB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (idMedicament)');
        $this->addSql('ALTER TABLE repertorier ADD CONSTRAINT FK_423881E32391866B FOREIGN KEY (praticien_id) REFERENCES praticien (id)');
        $this->addSql('ALTER TABLE repertorier ADD CONSTRAINT FK_423881E37F72333D FOREIGN KEY (visiteur_id) REFERENCES visiteur (idVisiteur)');
        $this->addSql('ALTER TABLE travailler ADD CONSTRAINT FK_90B2DF3D2391866B FOREIGN KEY (praticien_id) REFERENCES praticien (id)');
        $this->addSql('ALTER TABLE travailler ADD CONSTRAINT FK_90B2DF3D98260155 FOREIGN KEY (region_id) REFERENCES region (numRegion)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBB1D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBB2391866B FOREIGN KEY (praticien_id) REFERENCES praticien (id)');
        $this->addSql('ALTER TABLE visiteur ADD CONSTRAINT FK_4EA587B885C71A0D FOREIGN KEY (idProfil) REFERENCES profil (idProfil)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE echantillon DROP FOREIGN KEY FK_2C649BE7D38D2421');
        $this->addSql('ALTER TABLE echantillon DROP FOREIGN KEY FK_2C649BE718686CD0');
        $this->addSql('ALTER TABLE organiser DROP FOREIGN KEY FK_96054AFC1D06ADE3');
        $this->addSql('ALTER TABLE organiser DROP FOREIGN KEY FK_96054AFC2E036A07');
        $this->addSql('ALTER TABLE participer DROP FOREIGN KEY FK_EDBE16F82391866B');
        $this->addSql('ALTER TABLE participer DROP FOREIGN KEY FK_EDBE16F8D2E3ED2F');
        $this->addSql('ALTER TABLE praticien DROP FOREIGN KEY FK_D9A27D32195E0F0');
        $this->addSql('ALTER TABLE presenter DROP FOREIGN KEY FK_D3B56AC81D06ADE3');
        $this->addSql('ALTER TABLE presenter DROP FOREIGN KEY FK_D3B56AC8C575A9AA');
        $this->addSql('ALTER TABLE proposer DROP FOREIGN KEY FK_21866C15C1C5DC59');
        $this->addSql('ALTER TABLE proposer DROP FOREIGN KEY FK_21866C15AB0D61F7');
        $this->addSql('ALTER TABLE repertorier DROP FOREIGN KEY FK_423881E32391866B');
        $this->addSql('ALTER TABLE repertorier DROP FOREIGN KEY FK_423881E37F72333D');
        $this->addSql('ALTER TABLE travailler DROP FOREIGN KEY FK_90B2DF3D2391866B');
        $this->addSql('ALTER TABLE travailler DROP FOREIGN KEY FK_90B2DF3D98260155');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBB1D06ADE3');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBB2391866B');
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
