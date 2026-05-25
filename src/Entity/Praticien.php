<?php

namespace App\Entity;

use App\Repository\PraticienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PraticienRepository::class)]
#[ORM\Table(name: 'praticien', indexes: [
    new ORM\Index(name: 'idx_praticien_composite', columns: ['numeroSequentiel', 'idPraticien'])
])]
class Praticien
{
    #[ORM\Id]
    #[ORM\Column(name: 'numeroSequentiel', type: 'integer')]
    #[Groups(['praticien:read'])]
    private ?int $numeroSequentiel = null;

    #[ORM\Id]
    #[ORM\Column(name: 'idPraticien', type: 'integer')]
    #[Groups(['praticien:read', 'visite:read', 'visite:list'])]
    private ?int $idPraticien = null;

    #[ORM\ManyToOne(targetEntity: Specialite::class, inversedBy: 'praticiens')]
    #[Groups(['praticien:read', 'visite:read', 'visite:list'])]
    #[ORM\JoinColumn(name: 'numeroSequentiel', referencedColumnName: 'numeroSequentiel')]
    private ?Specialite $specialitePraticien = null;

    #[ORM\Column(name: 'nomPraticien', length: 50, nullable: true)]
    #[Groups(['praticien:read', 'visite:read', 'visite:list'])]
    private ?string $nomPraticien = null;

    #[ORM\Column(name: 'prenomPraticien', length: 50, nullable: true)]
    #[Groups(['praticien:read', 'visite:read', 'visite:list'])]
    private ?string $prenomPraticien = null;

    #[ORM\OneToMany(mappedBy: 'praticien', targetEntity: Travailler::class)]
    private Collection $travails;

    #[ORM\OneToMany(mappedBy: 'praticien', targetEntity: Visite::class)]
    private Collection $visites;

    #[ORM\OneToMany(mappedBy: 'praticien', targetEntity: Repertorier::class)]
    private Collection $repertories;

    #[ORM\OneToMany(mappedBy: 'praticien', targetEntity: Participer::class)]
    private Collection $participations;

    public function __construct()
    {
        $this->travails = new ArrayCollection();
        $this->visites = new ArrayCollection();
        $this->repertories = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }

    public function getNumeroSequentiel(): ?int { return $this->numeroSequentiel; }
    public function setNumeroSequentiel(int $numeroSequentiel): static { $this->numeroSequentiel = $numeroSequentiel; return $this; }

    public function getIdPraticien(): ?int { return $this->idPraticien; }
    public function setIdPraticien(int $idPraticien): static { $this->idPraticien = $idPraticien; return $this; }

    public function getSpecialitePraticien(): ?Specialite { return $this->specialitePraticien; }
    public function setSpecialitePraticien(?Specialite $specialitePraticien): static { $this->specialitePraticien = $specialitePraticien; return $this; }

    public function getNomPraticien(): ?string { return $this->nomPraticien; }
    public function setNomPraticien(?string $nomPraticien): static { $this->nomPraticien = $nomPraticien; return $this; }

    public function getPrenomPraticien(): ?string { return $this->prenomPraticien; }
    public function setPrenomPraticien(?string $prenomPraticien): static { $this->prenomPraticien = $prenomPraticien; return $this; }

    /** @return Collection<int, Travailler> */
    public function getTravails(): Collection { return $this->travails; }

    /** @return Collection<int, Visite> */
    public function getVisites(): Collection { return $this->visites; }

    /** @return Collection<int, Repertorier> */
    public function getRepertories(): Collection { return $this->repertories; }

    /** @return Collection<int, Participer> */
    public function getParticipations(): Collection { return $this->participations; }
}