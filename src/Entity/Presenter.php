<?php

namespace App\Entity;

use App\Repository\PresenterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PresenterRepository::class)]
#[ORM\Table(name: 'presenter')]
class Presenter
{
    #[ORM\Id]
    #[ORM\Column(name: 'idVisiteur', type: 'integer')]
    private ?int $visiteurId = null;

    #[ORM\Id]
    #[ORM\Column(name: 'numRegion', type: 'integer')]
    private ?int $regionId = null;

    #[ORM\Id]
    #[ORM\Column(name: 'dateAffect', type: 'string', length: 10)]
    private ?string $dateAffect = null;

    #[ORM\ManyToOne(targetEntity: Visiteur::class, inversedBy: 'presentations')]
    #[ORM\JoinColumn(name: 'idVisiteur', referencedColumnName: 'idVisiteur')]
    private ?Visiteur $visiteur = null;

    #[ORM\ManyToOne(targetEntity: Region::class, inversedBy: 'presentations')]
    #[ORM\JoinColumn(name: 'numRegion', referencedColumnName: 'numRegion')]
    private ?Region $region = null;

    public function getVisiteurId(): ?int { return $this->visiteurId; }
    public function setVisiteurId(int $visiteurId): static { $this->visiteurId = $visiteurId; return $this; }

    public function getRegionId(): ?int { return $this->regionId; }
    public function setRegionId(int $regionId): static { $this->regionId = $regionId; return $this; }

    public function getDateAffect(): ?string { return $this->dateAffect; }
    public function setDateAffect(string $dateAffect): static { $this->dateAffect = $dateAffect; return $this; }

    public function getDateAffectAsDateTime(): ?\DateTimeInterface
    {
        return $this->dateAffect ? new \DateTime($this->dateAffect) : null;
    }

    public function setDateAffectFromDateTime(\DateTimeInterface $date): static
    {
        $this->dateAffect = $date->format('Y-m-d');
        return $this;
    }

    public function getVisiteur(): ?Visiteur { return $this->visiteur; }
    public function setVisiteur(?Visiteur $visiteur): static { $this->visiteur = $visiteur; return $this; }

    public function getRegion(): ?Region { return $this->region; }
    public function setRegion(?Region $region): static { $this->region = $region; return $this; }
}