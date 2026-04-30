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
    #[ORM\ManyToOne(targetEntity: Visite::class, inversedBy: 'propositions')]
    #[ORM\JoinColumn(name: 'idVisite', referencedColumnName: 'idVisite')]
    private ?Visite $visite = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Medicament::class, inversedBy: 'propositions')]
    #[ORM\JoinColumn(name: 'idMedicament', referencedColumnName: 'idMedicament')]
    #[Groups(['visite:read'])]
    private ?Medicament $medicament = null;

    #[ORM\Column(name: 'nb_echantillon')]
    #[Groups(['visite:read'])]
    private ?int $quantite = null;

    public function getVisite(): ?Visite { return $this->visite; }
    public function setVisite(?Visite $visite): static { $this->visite = $visite; return $this; }

    public function getMedicament(): ?Medicament { return $this->medicament; }
    public function setMedicament(?Medicament $medicament): static { $this->medicament = $medicament; return $this; }

    public function getQuantite(): ?int { return $this->quantite; }
    public function setQuantite(int $quantite): static { $this->quantite = $quantite; return $this; }
}