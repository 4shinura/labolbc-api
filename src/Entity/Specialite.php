<?php

namespace App\Entity;

use App\Repository\SpecialiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SpecialiteRepository::class)]
#[ORM\Table(name: 'specialite')]
class Specialite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'numeroSequentiel')]
    #[Groups(['specialite:read', 'praticien:read', 'visite:read', 'visite:list'])]
    private ?int $numeroSequentiel = null;

    #[ORM\Column(length: 50)]
    #[Groups(['specialite:read'])]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'specialite', targetEntity: Praticien::class)]
    private Collection $praticiens;

    public function __construct()
    {
        $this->praticiens = new ArrayCollection();
    }

    public function getNumeroSequentiel(): ?int { return $this->numeroSequentiel; }
    public function setNumeroSequentiel(int $num): static { $this->numeroSequentiel = $num; return $this; }

    public function getLibelle(): ?string { return $this->libelle; }
    public function setLibelle(string $libelle): static { $this->libelle = $libelle; return $this; }

    /** @return Collection<int, Praticien> */
    public function getPraticiens(): Collection { return $this->praticiens; }
}