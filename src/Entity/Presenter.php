<?php

namespace App\Entity;

use App\Repository\PresenterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PresenterRepository::class)]
#[ORM\Table(name: 'presenter')]
class Presenter
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Visiteur::class, inversedBy: 'presentations')]
    #[ORM\JoinColumn(name: 'idVisiteur', referencedColumnName: 'idVisiteur')]
    private ?Visiteur $visiteur = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Region::class, inversedBy: 'presentations')]
    #[ORM\JoinColumn(name: 'numRegion', referencedColumnName: 'numRegion')]
    private ?Region $region = null;

    #[ORM\Id]
    #[ORM\Column(type: 'date', name: 'dateAffect')]
    private ?\DateTimeInterface $dateAffect = null;

    public function getVisiteur(): ?Visiteur { return $this->visiteur; }
    public function setVisiteur(?Visiteur $visiteur): static { $this->visiteur = $visiteur; return $this; }

    public function getRegion(): ?Region { return $this->region; }
    public function setRegion(?Region $region): static { $this->region = $region; return $this; }

    public function getDateAffect(): ?\DateTimeInterface { return $this->dateAffect; }
    public function setDateAffect(\DateTimeInterface $date): static { $this->dateAffect = $date; return $this; }
}