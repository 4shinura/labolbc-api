<?php

namespace App\Entity;

use App\Repository\ProposerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[ORM\Entity(repositoryClass: ProposerRepository::class)]
#[ORM\Table(name: 'proposer')]
class Proposer
{
    #[ORM\Id]
    #[ORM\Column(name: 'idVisite', type: 'integer')]
    private ?int $idVisite = null;

    #[ORM\Id]
    #[ORM\Column(name: 'idMedicament', type: 'integer')]
    private ?int $idMedicament = null;

    #[ORM\Column(name: 'nb_echantillon', type: 'integer', nullable: true)]
    private ?int $nb_echantillon = null;

    #[ORM\ManyToOne(targetEntity: Visite::class, inversedBy: 'propositions')]
    #[ORM\JoinColumn(name: 'idVisite', referencedColumnName: 'idVisite')]
    private ?Visite $visite = null;

    #[ORM\ManyToOne(targetEntity: Medicament::class, inversedBy: 'propositions')]
    #[ORM\JoinColumn(name: 'idMedicament', referencedColumnName: 'idMedicament')]
    private ?Medicament $medicament = null;

    public function getIdVisite(): ?int { return $this->idVisite; }
    public function setIdVisite(int $idVisite): static { $this->idVisite = $idVisite; return $this; }

    #[Groups(['visite:read', 'visite:list'])]
    public function getIdMedicament(): ?int { return $this->idMedicament; }
    public function setIdMedicament(int $idMedicament): static { $this->idMedicament = $idMedicament; return $this; }

    #[Groups(['visite:read', 'visite:list'])]
    #[SerializedName('nb_echantillon')]
    public function getNbEchantillons(): ?int { return $this->nb_echantillon; }
    public function setNbEchantillon(?int $nb_echantillon): static { $this->nb_echantillon = $nb_echantillon; return $this; }

    public function getVisite(): ?Visite { return $this->visite; }
    public function setVisite(?Visite $visite): static { $this->visite = $visite; return $this; }

    public function getMedicament(): ?Medicament { return $this->medicament; }
    public function setMedicament(?Medicament $medicament): static { $this->medicament = $medicament; return $this; }
}