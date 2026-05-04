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
    #[ORM\JoinColumn(name: 'praticien_id', referencedColumnName: 'id', nullable: false)]
    private ?Praticien $praticien = null;

    #[ORM\ManyToOne(targetEntity: Region::class, inversedBy: 'travails')]
    #[ORM\JoinColumn(name: 'region_id', referencedColumnName: 'numRegion', nullable: false)]
    private ?Region $region = null;

    #[ORM\Column(name: 'dateA', type: 'string', length: 10)]
    private ?string $dateA = null;

    public function getId(): ?int { return $this->id; }

    public function getPraticien(): ?Praticien { return $this->praticien; }
    public function setPraticien(?Praticien $praticien): static { $this->praticien = $praticien; return $this; }

    public function getRegion(): ?Region { return $this->region; }
    public function setRegion(?Region $region): static { $this->region = $region; return $this; }

    public function getDateA(): ?string { return $this->dateA; }
    public function setDateA(string $dateA): static { $this->dateA = $dateA; return $this; }
}