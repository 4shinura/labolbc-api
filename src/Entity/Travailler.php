<?php

namespace App\Entity;

use App\Repository\TravaillerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TravaillerRepository::class)]
#[ORM\Table(name: 'travailler')]
class Travailler
{
    #[ORM\Id]
    #[ORM\Column(name: 'numeroSequentiel', type: 'integer')]
    private ?int $numSeq = null;

    #[ORM\Id]
    #[ORM\Column(name: 'idPraticien', type: 'integer')]
    private ?int $idPraticien = null;

    #[ORM\Id]
    #[ORM\Column(name: 'numRegion', type: 'integer')]
    private ?int $regionId = null;

    #[ORM\Id]
    #[ORM\Column(name: 'dateA', type: 'string', length: 10)]
    private ?string $dateA = null;

    #[ORM\ManyToOne(targetEntity: Praticien::class, inversedBy: 'travails')]
    #[ORM\JoinColumn(name: 'numeroSequentiel', referencedColumnName: 'numeroSequentiel')]
    #[ORM\JoinColumn(name: 'idPraticien', referencedColumnName: 'idPraticien')]
    private ?Praticien $praticien = null;

    #[ORM\ManyToOne(targetEntity: Region::class, inversedBy: 'travails')]
    #[ORM\JoinColumn(name: 'numRegion', referencedColumnName: 'numRegion')]
    private ?Region $region = null;

    public function getNumSeq(): ?int { return $this->numSeq; }
    public function setNumSeq(int $numSeq): static { $this->numSeq = $numSeq; return $this; }

    public function getIdPraticien(): ?int { return $this->idPraticien; }
    public function setIdPraticien(int $idPraticien): static { $this->idPraticien = $idPraticien; return $this; }

    public function getRegionId(): ?int { return $this->regionId; }
    public function setRegionId(int $regionId): static { $this->regionId = $regionId; return $this; }

    public function getDateA(): ?string { return $this->dateA; }
    public function setDateA(string $dateA): static { $this->dateA = $dateA; return $this; }

    public function getDateAAsDateTime(): ?\DateTimeInterface
    {
        return $this->dateA ? new \DateTime($this->dateA) : null;
    }

    public function setDateAFromDateTime(\DateTimeInterface $date): static
    {
        $this->dateA = $date->format('Y-m-d');
        return $this;
    }

    public function getPraticien(): ?Praticien { return $this->praticien; }
    public function setPraticien(?Praticien $praticien): static { $this->praticien = $praticien; return $this; }

    public function getRegion(): ?Region { return $this->region; }
    public function setRegion(?Region $region): static { $this->region = $region; return $this; }
}