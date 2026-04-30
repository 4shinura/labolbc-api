<?php

namespace App\Entity;

use App\Repository\VisiteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VisiteurRepository::class)]
#[ORM\Table(name: 'visiteur')]
class Visiteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idVisiteur')]
    #[Groups(['visiteur:read', 'visite:read'])]
    private ?int $id = null;

    #[ORM\Column(name: 'nomVisiteur', length: 255)]
    #[Groups(['visiteur:read'])]
    private ?string $nom = null;

    #[ORM\OneToOne(inversedBy: 'visiteur', targetEntity: Profil::class)]
    #[ORM\JoinColumn(name: 'idProfil', referencedColumnName: 'idProfil')]
    private ?Profil $profil = null;

    #[ORM\ManyToOne(targetEntity: Region::class, inversedBy: 'visiteurs')]
    #[ORM\JoinColumn(name: 'numRegion', referencedColumnName: 'numRegion')]
    #[Groups(['visiteur:read'])]
    private ?Region $region = null;

    #[ORM\OneToMany(mappedBy: 'visiteur', targetEntity: Visite::class, orphanRemoval: true)]
    private Collection $visites;

    #[ORM\ManyToMany(targetEntity: ActiviteComplementaire::class, mappedBy: 'visiteurs')]
    private Collection $activites;

    public function __construct()
    {
        $this->visites = new ArrayCollection();
        $this->activites = new ArrayCollection();
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): static
    {
        $this->profil = $profil;
        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return Collection<int, Visite>
     */
    public function getVisites(): Collection
    {
        return $this->visites;
    }

    /**
     * @return Collection<int, ActiviteComplementaire>
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }
}