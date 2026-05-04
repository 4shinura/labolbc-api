<?php

namespace App\Entity;

use App\Repository\ParticiperRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticiperRepository::class)]
#[ORM\Table(name: 'participer')]
class Participer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Praticien::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(name: 'praticien_id', referencedColumnName: 'id', nullable: false)]
    private ?Praticien $praticien = null;

    #[ORM\ManyToOne(targetEntity: Ac::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(name: 'ac_id', referencedColumnName: 'idAC', nullable: false)]
    private ?Ac $ac = null;

    public function getId(): ?int { return $this->id; }

    public function getPraticien(): ?Praticien { return $this->praticien; }
    public function setPraticien(?Praticien $praticien): static { $this->praticien = $praticien; return $this; }

    public function getAc(): ?Ac { return $this->ac; }
    public function setAc(?Ac $ac): static { $this->ac = $ac; return $this; }
}