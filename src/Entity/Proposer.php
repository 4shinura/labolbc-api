<?php

namespace App\Entity;

use App\Repository\ProposerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProposerRepository::class)]
#[ORM\Table(name: 'proposer')]
class Proposer
{
    #[ORM\Id]
    #[ORM\Column(name: 'idVisite', type: 'integer')]
    private ?int $visiteId = null;

    #[ORM\Id]
    #[ORM\Column(name: 'idMedicament', type: 'integer')]
    private ?int $medicamentId = null;

    #[ORM\Column(name: 'nb_echantillon')]
    #[Groups(['visite:read'])]
    private ?int $quantite = null;

    #[ORM\ManyToOne(targetEntity: Visite::class, inversedBy: 'propositions')]
    #[ORM\JoinColumn(name: 'idVisite', referencedColumnName: 'idVisite')]
    private ?Visite $visite = null;

    #[ORM\ManyToOne(targetEntity: Medicament::class, inversedBy: 'propositions')]
    #[ORM\JoinColumn(name: 'idMedicament', referencedColumnName: 'idMedicament')]
    #[Groups(['visite:read'])]
    private ?Medicament $medicament = null;

    public function getVisiteId(): ?int { return $this->visiteId; }
    public function setVisiteId(int $visiteId): static { $this->visiteId = $visiteId; return $this; }

    public function getMedicamentId(): ?int { return $this->medicamentId; }
    public function setMedicamentId(int $medicamentId): static { $this->medicamentId = $medicamentId; return $this; }

    public function getQuantite(): ?int { return $this->quantite; }
    public function setQuantite(int $quantite): static { $this->quantite = $quantite; return $this; }

    public function getVisite(): ?Visite { return $this->visite; }
    public function setVisite(?Visite $visite): static { $this->visite = $visite; return $this; }

    public function getMedicament(): ?Medicament { return $this->medicament; }
    public function setMedicament(?Medicament $medicament): static { $this->medicament = $medicament; return $this; }
}