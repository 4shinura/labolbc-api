<?php

namespace App\Entity;

use App\Repository\ParticiperRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticiperRepository::class)]
#[ORM\Table(name: 'participer')]
class Participer
{
    #[ORM\Id]
    #[ORM\Column(name: 'numeroSequentiel', type: 'integer')]
    private ?int $numSeq = null;

    #[ORM\Id]
    #[ORM\Column(name: 'idPraticien', type: 'integer')]
    private ?int $idPraticien = null;

    #[ORM\Id]
    #[ORM\Column(name: 'idAC', type: 'integer')]
    private ?int $acId = null;

    #[ORM\ManyToOne(targetEntity: Praticien::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(name: 'numeroSequentiel', referencedColumnName: 'numeroSequentiel')]
    #[ORM\JoinColumn(name: 'idPraticien', referencedColumnName: 'idPraticien')]
    private ?Praticien $praticien = null;

    #[ORM\ManyToOne(targetEntity: Ac::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(name: 'idAC', referencedColumnName: 'idAC')]
    private ?Ac $ac = null;

    public function getNumSeq(): ?int { return $this->numSeq; }
    public function setNumSeq(int $numSeq): static { $this->numSeq = $numSeq; return $this; }

    public function getIdPraticien(): ?int { return $this->idPraticien; }
    public function setIdPraticien(int $idPraticien): static { $this->idPraticien = $idPraticien; return $this; }

    public function getAcId(): ?int { return $this->acId; }
    public function setAcId(int $acId): static { $this->acId = $acId; return $this; }

    public function getPraticien(): ?Praticien { return $this->praticien; }
    public function setPraticien(?Praticien $praticien): static { $this->praticien = $praticien; return $this; }

    public function getAc(): ?Ac { return $this->ac; }
    public function setAc(?Ac $ac): static { $this->ac = $ac; return $this; }
}