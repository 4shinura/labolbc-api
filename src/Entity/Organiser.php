<?php

namespace App\Entity;

use App\Repository\OrganiserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganiserRepository::class)]
#[ORM\Table(name: 'organiser')]
class Organiser
{
    #[ORM\Id]
    #[ORM\Column(name: 'idVisiteur', type: 'integer')]
    private ?int $visiteurId = null;

    #[ORM\Id]
    #[ORM\Column(name: 'idAC', type: 'integer')]
    private ?int $acId = null;

    #[ORM\ManyToOne(targetEntity: Visiteur::class, inversedBy: 'organisations')]
    #[ORM\JoinColumn(name: 'idVisiteur', referencedColumnName: 'idVisiteur')]
    private ?Visiteur $visiteur = null;

    #[ORM\ManyToOne(targetEntity: Ac::class, inversedBy: 'organisations')]
    #[ORM\JoinColumn(name: 'idAC', referencedColumnName: 'idAC')]
    private ?Ac $ac = null;

    public function getVisiteurId(): ?int { return $this->visiteurId; }
    public function setVisiteurId(int $visiteurId): static { $this->visiteurId = $visiteurId; return $this; }

    public function getAcId(): ?int { return $this->acId; }
    public function setAcId(int $acId): static { $this->acId = $acId; return $this; }

    public function getVisiteur(): ?Visiteur { return $this->visiteur; }
    public function setVisiteur(?Visiteur $visiteur): static { $this->visiteur = $visiteur; return $this; }

    public function getAc(): ?Ac { return $this->ac; }
    public function setAc(?Ac $ac): static { $this->ac = $ac; return $this; }
}