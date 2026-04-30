<?php

namespace App\Entity;

use App\Repository\TravaillerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TravaillerRepository::class)]
#[ORM\Table(name: 'travailler')]
class Travailler
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Praticien::class, inversedBy: 'travails')]
    #[ORM\JoinColumn(name: 'numeroSequentiel', referencedColumnName: 'numeroSequentiel')]
    #[ORM\JoinColumn(name: 'idPraticien', referencedColumnName: 'idPraticien')]
    private ?Praticien $praticien = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Region::class)]
    #[ORM\JoinColumn(name: 'numRegion', referencedColumnName: 'numRegion')]
    private ?Region $region = null;

    #[ORM\Id]
    #[ORM\Column(type: 'date', name: 'dateA')]
    private ?\DateTimeInterface $date = null;

    public function getPraticien(): ?Praticien { return $this->praticien; }
    public function setPraticien(?Praticien $praticien): static { $this->praticien = $praticien; return $this; }

    public function getRegion(): ?Region { return $this->region; }
    public function setRegion(?Region $region): static { $this->region = $region; return $this; }

    public function getDate(): ?\DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $date): static { $this->date = $date; return $this; }
}