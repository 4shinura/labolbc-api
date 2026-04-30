<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
#[ORM\Table(name: 'region')]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'numRegion')]
    #[Groups(['region:read', 'praticien:read'])]
    private ?int $id = null;

    #[ORM\Column(name: 'libelleRegion', length: 255)]
    #[Groups(['region:read'])]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: Praticien::class)]
    private Collection $praticiens;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: Visiteur::class)]
    private Collection $visiteurs;

    public function __construct()
    {
        $this->praticiens = new ArrayCollection();
        $this->visiteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Collection<int, Praticien>
     */
    public function getPraticiens(): Collection
    {
        return $this->praticiens;
    }

    /**
     * @return Collection<int, Visiteur>
     */
    public function getVisiteurs(): Collection
    {
        return $this->visiteurs;
    }
}