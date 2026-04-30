<?php

namespace App\Entity;

use App\Repository\TravaillerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TravaillerRepository::class)]
#[ORM\Table(name: 'travailler')]
class Travailler
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Praticien::class, inversedBy: 'travails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Praticien $praticien = null;

    #[ORM\ManyToOne(targetEntity: Region::class, inversedBy: 'travails')]
    #[ORM\JoinColumn(name: 'numRegion', referencedColumnName: 'numRegion', nullable: false)]
    private ?Region $region = null;

    #[ORM\Column(type: 'date', name: 'dateA')]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int { return $this->id; }
    public function getPraticien(): ?Praticien { return $this->praticien; }
    public function setPraticien(?Praticien $praticien): static { $this->praticien = $praticien; return $this; }
    public function getRegion(): ?Region { return $this->region; }
    public function setRegion(?Region $region): static { $this->region = $region; return $this; }
    public function getDate(): ?\DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $date): static { $this->date = $date; return $this; }
}