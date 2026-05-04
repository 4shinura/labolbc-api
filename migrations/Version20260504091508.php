<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504091508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE affectation');
        $this->addSql('ALTER TABLE echantillon ADD CONSTRAINT FK_2C649BE7D38D2421 FOREIGN KEY (idVisite) REFERENCES visite (idVisite)');
        $this->addSql('ALTER TABLE echantillon ADD CONSTRAINT FK_2C649BE718686CD0 FOREIGN KEY (idMedicament) REFERENCES medicament (idMedicament)');
        $this->addSql('ALTER TABLE organiser ADD CONSTRAINT FK_96054AFC1D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        $this->addSql('ALTER TABLE organiser ADD CONSTRAINT FK_96054AFC2E036A07 FOREIGN KEY (idAC) REFERENCES ac (idAC)');
        $this->addSql('ALTER TABLE participer ADD CONSTRAINT FK_EDBE16F8F8960AE6F1700C85 FOREIGN KEY (numeroSequentiel, idPraticien) REFERENCES praticien (numeroSequentiel, idPraticien)');
        $this->addSql('ALTER TABLE participer ADD CONSTRAINT FK_EDBE16F82E036A07 FOREIGN KEY (idAC) REFERENCES ac (idAC)');
        $this->addSql('ALTER TABLE presenter ADD CONSTRAINT FK_D3B56AC81D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        $this->addSql('ALTER TABLE presenter ADD CONSTRAINT FK_D3B56AC8C575A9AA FOREIGN KEY (numRegion) REFERENCES region (numRegion)');
        $this->addSql('ALTER TABLE profil ADD email VARCHAR(100) NOT NULL, DROP username');
        $this->addSql('ALTER TABLE proposer ADD CONSTRAINT FK_21866C15D38D2421 FOREIGN KEY (idVisite) REFERENCES visite (idVisite)');
        $this->addSql('ALTER TABLE proposer ADD CONSTRAINT FK_21866C1518686CD0 FOREIGN KEY (idMedicament) REFERENCES medicament (idMedicament)');
        $this->addSql('ALTER TABLE repertorier ADD CONSTRAINT FK_423881E3F8960AE6F1700C85 FOREIGN KEY (numeroSequentiel, idPraticien) REFERENCES praticien (numeroSequentiel, idPraticien)');
        $this->addSql('ALTER TABLE repertorier ADD CONSTRAINT FK_423881E31D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        $this->addSql('ALTER TABLE travailler ADD CONSTRAINT FK_90B2DF3DF8960AE6F1700C85 FOREIGN KEY (numeroSequentiel, idPraticien) REFERENCES praticien (numeroSequentiel, idPraticien)');
        $this->addSql('ALTER TABLE travailler ADD CONSTRAINT FK_90B2DF3DC575A9AA FOREIGN KEY (numRegion) REFERENCES region (numRegion)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBB1D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES visiteur (idVisiteur)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBB2391866B FOREIGN KEY (praticien_id) REFERENCES praticien (id)');
        $this->addSql('ALTER TABLE visiteur ADD CONSTRAINT FK_4EA587B885C71A0D FOREIGN KEY (idProfil) REFERENCES profil (idProfil)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE affectation (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, numeroSequentiel INT DEFAULT NULL, idPraticien INT DEFAULT NULL, numRegion INT DEFAULT NULL, INDEX IDX_F4DD61D3F8960AE6F1700C85 (numeroSequentiel, idPraticien), INDEX IDX_F4DD61D3C575A9AA (numRegion), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE echantillon DROP FOREIGN KEY FK_2C649BE7D38D2421');
        $this->addSql('ALTER TABLE echantillon DROP FOREIGN KEY FK_2C649BE718686CD0');
        $this->addSql('ALTER TABLE organiser DROP FOREIGN KEY FK_96054AFC1D06ADE3');
        $this->addSql('ALTER TABLE organiser DROP FOREIGN KEY FK_96054AFC2E036A07');
        $this->addSql('ALTER TABLE participer DROP FOREIGN KEY FK_EDBE16F8F8960AE6F1700C85');
        $this->addSql('ALTER TABLE participer DROP FOREIGN KEY FK_EDBE16F82E036A07');
        $this->addSql('ALTER TABLE presenter DROP FOREIGN KEY FK_D3B56AC81D06ADE3');
        $this->addSql('ALTER TABLE presenter DROP FOREIGN KEY FK_D3B56AC8C575A9AA');
        $this->addSql('ALTER TABLE profil ADD username VARCHAR(50) NOT NULL, DROP email');
        $this->addSql('ALTER TABLE proposer DROP FOREIGN KEY FK_21866C15D38D2421');
        $this->addSql('ALTER TABLE proposer DROP FOREIGN KEY FK_21866C1518686CD0');
        $this->addSql('ALTER TABLE repertorier DROP FOREIGN KEY FK_423881E3F8960AE6F1700C85');
        $this->addSql('ALTER TABLE repertorier DROP FOREIGN KEY FK_423881E31D06ADE3');
        $this->addSql('ALTER TABLE travailler DROP FOREIGN KEY FK_90B2DF3DF8960AE6F1700C85');
        $this->addSql('ALTER TABLE travailler DROP FOREIGN KEY FK_90B2DF3DC575A9AA');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBB1D06ADE3');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBB2391866B');
        $this->addSql('ALTER TABLE visiteur DROP FOREIGN KEY FK_4EA587B885C71A0D');
    }
}
