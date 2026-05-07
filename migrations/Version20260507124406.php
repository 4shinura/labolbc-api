<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260507124406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE echantillon CHANGE quantite quantite INT NOT NULL');
        $this->addSql('ALTER TABLE echantillon RENAME INDEX idx_echantillon_visite TO IDX_2C649BE7D38D2421');
        $this->addSql('ALTER TABLE echantillon RENAME INDEX idx_echantillon_medicament TO IDX_2C649BE718686CD0');
        $this->addSql('ALTER TABLE organiser RENAME INDEX fk_organiser_ac TO IDX_96054AFC2E036A07');
        $this->addSql('ALTER TABLE participer RENAME INDEX fk_participer_ac TO IDX_EDBE16F82E036A07');
        $this->addSql('ALTER TABLE presenter RENAME INDEX fk_presenter_region TO IDX_D3B56AC8C575A9AA');
        $this->addSql('ALTER TABLE proposer RENAME INDEX fk_proposer_medicament TO IDX_21866C1518686CD0');
        $this->addSql('ALTER TABLE repertorier RENAME INDEX fk_repertorier_visiteur TO IDX_423881E31D06ADE3');
        $this->addSql('ALTER TABLE travailler RENAME INDEX fk_travailler_region TO IDX_90B2DF3DC575A9AA');
        $this->addSql('ALTER TABLE visite RENAME INDEX idx_visite_id_visiteur TO IDX_B09C8CBB1D06ADE3');
        $this->addSql('ALTER TABLE visite RENAME INDEX idx_visite_praticien TO IDX_B09C8CBBF8960AE6F1700C85');
        $this->addSql('ALTER TABLE visiteur RENAME INDEX uniq_visiteur_idprofil TO UNIQ_4EA587B885C71A0D');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE echantillon CHANGE quantite quantite INT DEFAULT NULL');
        $this->addSql('ALTER TABLE echantillon RENAME INDEX idx_2c649be7d38d2421 TO IDX_ECHANTILLON_VISITE');
        $this->addSql('ALTER TABLE echantillon RENAME INDEX idx_2c649be718686cd0 TO IDX_ECHANTILLON_MEDICAMENT');
        $this->addSql('ALTER TABLE organiser RENAME INDEX idx_96054afc2e036a07 TO FK_ORGANISER_AC');
        $this->addSql('ALTER TABLE participer RENAME INDEX idx_edbe16f82e036a07 TO FK_PARTICIPER_AC');
        $this->addSql('ALTER TABLE presenter RENAME INDEX idx_d3b56ac8c575a9aa TO FK_PRESENTER_REGION');
        $this->addSql('ALTER TABLE proposer RENAME INDEX idx_21866c1518686cd0 TO FK_PROPOSER_MEDICAMENT');
        $this->addSql('ALTER TABLE repertorier RENAME INDEX idx_423881e31d06ade3 TO FK_REPERTORIER_VISITEUR');
        $this->addSql('ALTER TABLE travailler RENAME INDEX idx_90b2df3dc575a9aa TO FK_TRAVAILLER_REGION');
        $this->addSql('ALTER TABLE visite RENAME INDEX idx_b09c8cbbf8960ae6f1700c85 TO IDX_VISITE_PRATICIEN');
        $this->addSql('ALTER TABLE visite RENAME INDEX idx_b09c8cbb1d06ade3 TO IDX_VISITE_ID_VISITEUR');
        $this->addSql('ALTER TABLE visiteur RENAME INDEX uniq_4ea587b885c71a0d TO UNIQ_VISITEUR_IDPROFIL');
    }
}
