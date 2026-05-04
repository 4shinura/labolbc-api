<?php

namespace App\Entity;

use App\Repository\RepertorierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepertorierRepository::class)]
#[ORM\Table(name: 'repertorier')]
class Repertorier
{
    #[ORM\Id]
    #[ORM\Column(name: 'numeroSequentiel', type: 'integer')]
    private ?int $numSeq = null;

    #[ORM\Id]
    #[ORM\Column(name: 'idPraticien', type: 'integer')]
    private ?int $idPraticien = null;

    #[ORM\Id]
    #[ORM\Column(name: 'idVisiteur', type: 'integer')]
    private ?int $visiteurId = null;

    #[ORM\ManyToOne(targetEntity: Praticien::class, inversedBy: 'repertories')]
    #[ORM\JoinColumn(name: 'numeroSequentiel', referencedColumnName: 'numeroSequentiel')]
    #[ORM\JoinColumn(name: 'idPraticien', referencedColumnName: 'idPraticien')]
    private ?Praticien $praticien = null;

    #[ORM\ManyToOne(targetEntity: Visiteur::class, inversedBy: 'repertories')]
    #[ORM\JoinColumn(name: 'idVisiteur', referencedColumnName: 'idVisiteur')]
    private ?Visiteur $visiteur = null;

    public function getNumSeq(): ?int { return $this->numSeq; }
    public function setNumSeq(int $numSeq): static { $this->numSeq = $numSeq; return $this; }

    public function getIdPraticien(): ?int { return $this->idPraticien; }
    public function setIdPraticien(int $idPraticien): static { $this->idPraticien = $idPraticien; return $this; }

    public function getVisiteurId(): ?int { return $this->visiteurId; }
    public function setVisiteurId(int $visiteurId): static { $this->visiteurId = $visiteurId; return $this; }

    public function getPraticien(): ?Praticien { return $this->praticien; }
    public function setPraticien(?Praticien $praticien): static { $this->praticien = $praticien; return $this; }

    public function getVisiteur(): ?Visiteur { return $this->visiteur; }
    public function setVisiteur(?Visiteur $visiteur): static { $this->visiteur = $visiteur; return $this; }
}