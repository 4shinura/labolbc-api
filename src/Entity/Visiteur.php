<?php

namespace App\Entity;

use App\Repository\VisiteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: VisiteurRepository::class)]
#[ORM\Table(name: 'visiteur')]
class Visiteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idVisiteur')]
    #[Groups(['visiteur:read', 'visite:read', 'visite:list'])]
    private ?int $idVisiteur = null;

    #[ORM\Column(name: 'nomVisiteur', length: 50, nullable: true)]
    #[Groups(['visiteur:read', 'visite:read', 'visite:list'])]
    private ?string $nomVisiteur = null;

    #[ORM\OneToOne(inversedBy: 'visiteur', targetEntity: Profil::class)]
    #[ORM\JoinColumn(name: 'idProfil', referencedColumnName: 'idProfil')]
    private ?Profil $profil = null;

    #[ORM\OneToMany(mappedBy: 'visiteur', targetEntity: Visite::class)]
    private Collection $visites;

    #[ORM\OneToMany(mappedBy: 'visiteur', targetEntity: Presenter::class)]
    private Collection $presentations;

    #[ORM\OneToMany(mappedBy: 'visiteur', targetEntity: Organiser::class)]
    private Collection $organisations;

    #[ORM\OneToMany(mappedBy: 'visiteur', targetEntity: Repertorier::class)]
    private Collection $repertories;

    public function __construct()
    {
        $this->visites = new ArrayCollection();
        $this->presentations = new ArrayCollection();
        $this->organisations = new ArrayCollection();
        $this->repertories = new ArrayCollection();
    }

    public function getIdVisiteur(): ?int { return $this->idVisiteur; }
    public function setIdVisiteur(int $idVisiteur): static { $this->idVisiteur = $idVisiteur; return $this; }

    public function getNomVisiteur(): ?string { return $this->nomVisiteur; }
    public function setNomVisiteur(string $nomVisiteur): static { $this->nomVisiteur = $nomVisiteur; return $this; }

    public function getProfil(): ?Profil { return $this->profil; }
    public function setProfil(?Profil $profil): static { $this->profil = $profil; return $this; }

    /** @return Collection<int, Visite> */
    public function getVisites(): Collection { return $this->visites; }

    /** @return Collection<int, Presenter> */
    public function getPresentations(): Collection { return $this->presentations; }

    /** @return Collection<int, Organiser> */
    public function getOrganisations(): Collection { return $this->organisations; }

    /** @return Collection<int, Repertorier> */
    public function getRepertories(): Collection { return $this->repertories; }
}