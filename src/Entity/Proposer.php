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
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Visite::class, inversedBy: 'propositions')]
    #[ORM\JoinColumn(name: 'visite_id', referencedColumnName: 'idVisite', nullable: false)]
    private ?Visite $visite = null;

    #[ORM\ManyToOne(targetEntity: Medicament::class, inversedBy: 'propositions')]
    #[ORM\JoinColumn(name: 'medicament_id', referencedColumnName: 'idMedicament', nullable: false)]
    #[Groups(['visite:read'])]
    private ?Medicament $medicament = null;

    #[ORM\Column]
    #[Groups(['visite:read'])]
    private ?int $quantite = null;

    public function getId(): ?int { return $this->id; }

    public function getVisite(): ?Visite { return $this->visite; }
    public function setVisite(?Visite $visite): static { $this->visite = $visite; return $this; }

    public function getMedicament(): ?Medicament { return $this->medicament; }
    public function setMedicament(?Medicament $medicament): static { $this->medicament = $medicament; return $this; }

    public function getQuantite(): ?int { return $this->quantite; }
    public function setQuantite(int $quantite): static { $this->quantite = $quantite; return $this; }
}