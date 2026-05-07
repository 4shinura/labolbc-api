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
    private ?int $idVisiteur = null;

    #[ORM\Id]
    #[ORM\Column(name: 'idAC', type: 'integer')]
    private ?int $idAC = null;

    #[ORM\ManyToOne(targetEntity: Visiteur::class, inversedBy: 'organisations')]
    #[ORM\JoinColumn(name: 'idVisiteur', referencedColumnName: 'idVisiteur')]
    private ?Visiteur $visiteur = null;

    #[ORM\ManyToOne(targetEntity: Ac::class, inversedBy: 'organisations')]
    #[ORM\JoinColumn(name: 'idAC', referencedColumnName: 'idAC')]
    private ?Ac $ac = null;

    public function getIdVisiteur(): ?int { return $this->idVisiteur; }
    public function setIdVisiteur(int $idVisiteur): static { $this->idVisiteur = $idVisiteur; return $this; }

    public function getIdAC(): ?int { return $this->idAC; }
    public function setIdAC(int $idAC): static { $this->idAC = $idAC; return $this; }

    public function getVisiteur(): ?Visiteur { return $this->visiteur; }
    public function setVisiteur(?Visiteur $visiteur): static { $this->visiteur = $visiteur; return $this; }

    public function getAc(): ?Ac { return $this->ac; }
    public function setAc(?Ac $ac): static { $this->ac = $ac; return $this; }
}