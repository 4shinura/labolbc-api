<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
#[ORM\Table(name: 'region')]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'numRegion')]
    #[Groups(['region:read'])]
    private ?int $numRegion = null;

    #[ORM\Column(name: 'libelleRegion', length: 50)]
    #[Groups(['region:read'])]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: Travailler::class)]
    private Collection $travails;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: Presenter::class)]
    private Collection $presentations;

    public function __construct()
    {
        $this->travails = new ArrayCollection();
        $this->presentations = new ArrayCollection();
    }

    public function getNumRegion(): ?int { return $this->numRegion; }
    public function setNumRegion(int $numRegion): static { $this->numRegion = $numRegion; return $this; }

    public function getLibelle(): ?string { return $this->libelle; }
    public function setLibelle(string $libelle): static { $this->libelle = $libelle; return $this; }

    /** @return Collection<int, Travailler> */
    public function getTravails(): Collection { return $this->travails; }

    /** @return Collection<int, Presenter> */
    public function getPresentations(): Collection { return $this->presentations; }
}