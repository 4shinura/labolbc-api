<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260505124303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participer DROP FOREIGN KEY FK_EDBE16F82391866B');
        $this->addSql('ALTER TABLE repertorier DROP FOREIGN KEY FK_423881E32391866B');
        $this->addSql('ALTER TABLE travailler DROP FOREIGN KEY FK_90B2DF3D2391866B');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBB2391866B');
        $this->addSql('ALTER TABLE praticien DROP FOREIGN KEY FK_D9A27D3F8960AE6');
        $this->addSql('DROP INDEX IDX_D9A27D3F8960AE6 ON praticien');
        $this->addSql('ALTER TABLE praticien MODIFY idPraticien INT NOT NULL');
        $this->addSql('ALTER TABLE praticien ADD id INT AUTO_INCREMENT NOT NULL, ADD specialite_id INT DEFAULT NULL, CHANGE idPraticien idPraticien INT NOT NULL, CHANGE numeroSequentiel numeroSequentiel INT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE praticien ADD CONSTRAINT `FK_D9A27D32195E0F0` FOREIGN KEY (specialite_id) REFERENCES specialite (numeroSequentiel) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D9A27D32195E0F0 ON praticien (specialite_id)');
        $this->addSql('ALTER TABLE participer ADD CONSTRAINT `FK_EDBE16F82391866B` FOREIGN KEY (praticien_id) REFERENCES praticien (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE repertorier ADD CONSTRAINT `FK_423881E32391866B` FOREIGN KEY (praticien_id) REFERENCES praticien (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE travailler ADD CONSTRAINT `FK_90B2DF3D2391866B` FOREIGN KEY (praticien_id) REFERENCES praticien (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT `FK_B09C8CBB2391866B` FOREIGN KEY (praticien_id) REFERENCES praticien (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
