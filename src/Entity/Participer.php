<?php

namespace App\Entity;

use App\Repository\ParticiperRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticiperRepository::class)]
#[ORM\Table(name: 'participer')]
class Participer
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Praticien::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(name: 'numeroSequentiel', referencedColumnName: 'numeroSequentiel')]
    #[ORM\JoinColumn(name: 'idPraticien', referencedColumnName: 'idPraticien')]
    private ?Praticien $praticien = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Ac::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(name: 'idAC', referencedColumnName: 'idAC')]
    private ?Ac $ac = null;

    public function getPraticien(): ?Praticien { return $this->praticien; }
    public function setPraticien(?Praticien $praticien): static { $this->praticien = $praticien; return $this; }

    public function getAc(): ?Ac { return $this->ac; }
    public function setAc(?Ac $ac): static { $this->ac = $ac; return $this; }
}