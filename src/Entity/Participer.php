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
    private ?int $numeroSequentiel = null;

    #[ORM\Id]
    #[ORM\Column(name: 'idPraticien', type: 'integer')]
    private ?int $idPraticien = null;

    #[ORM\Id]
    #[ORM\Column(name: 'idAC', type: 'integer')]
    private ?int $idAC = null;

    #[ORM\ManyToOne(targetEntity: Praticien::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(name: 'numeroSequentiel', referencedColumnName: 'numeroSequentiel')]
    #[ORM\JoinColumn(name: 'idPraticien', referencedColumnName: 'idPraticien')]
    private ?Praticien $praticien = null;

    #[ORM\ManyToOne(targetEntity: Ac::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(name: 'idAC', referencedColumnName: 'idAC')]
    private ?Ac $ac = null;

    public function getNumeroSequentiel(): ?int { return $this->numeroSequentiel; }
    public function setNumeroSequentiel(int $numeroSequentiel): static { $this->numeroSequentiel = $numeroSequentiel; return $this; }

    public function getIdPraticien(): ?int { return $this->idPraticien; }
    public function setIdPraticien(int $idPraticien): static { $this->idPraticien = $idPraticien; return $this; }

    public function getIdAC(): ?int { return $this->idAC; }
    public function setIdAC(int $idAC): static { $this->idAC = $idAC; return $this; }

    public function getPraticien(): ?Praticien { return $this->praticien; }
    public function setPraticien(?Praticien $praticien): static { $this->praticien = $praticien; return $this; }

    public function getAc(): ?Ac { return $this->ac; }
    public function setAc(?Ac $ac): static { $this->ac = $ac; return $this; }
}