<?php

namespace App\Entity;

use App\Repository\PraticienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PraticienRepository::class)]
#[ORM\Table(name: 'praticien')]
class Praticien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'numeroSequentiel')]
    #[Groups(['praticien:read', 'visite:read'])]
    private ?int $numSeq = null;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(name: 'idPraticien')]
    #[Groups(['praticien:read', 'visite:read'])]
    private ?int $id = null;

    #[ORM\Column(name: 'nomPraticien', length: 255)]
    #[Groups(['praticien:read', 'visite:read'])]
    private ?string $nom = null;

    #[ORM\Column(name: 'prenomPraticien', length: 255)]
    #[Groups(['praticien:read', 'visite:read'])]
    private ?string $prenom = null;

    #[ORM\ManyToOne(targetEntity: Specialite::class, inversedBy: 'praticiens')]
    #[ORM\JoinColumn(name: 'idSpecialite', referencedColumnName: 'id')]
    #[Groups(['praticien:read'])]
    private ?Specialite $specialite = null;

    #[ORM\ManyToOne(targetEntity: Region::class, inversedBy: 'praticiens')]
    #[ORM\JoinColumn(name: 'numRegion', referencedColumnName: 'numRegion')]
    #[Groups(['praticien:read'])]
    private ?Region $region = null;

    #[ORM\Column(name: 'dateAffectation', type: 'date', nullable: true)]
    #[Groups(['praticien:read'])]
    private ?\DateTimeInterface $dateAffectation = null;

    #[ORM\OneToMany(mappedBy: 'praticien', targetEntity: Visite::class)]
    private Collection $visites;

    #[ORM\OneToMany(mappedBy: 'praticien', targetEntity: Affectation::class, orphanRemoval: true)]
    #[ORM\OrderBy(['date' => 'DESC'])]
    private Collection $affectations;

    public function __construct()
    {
        $this->visites = new ArrayCollection();
        $this->affectations = new ArrayCollection();
    }

    public function getNumSeq(): ?int
    {
        return $this->numSeq;
    }

    public function setNumSeq(int $numSeq): static
    {
        $this->numSeq = $numSeq;
        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(?Specialite $specialite): static
    {
        $this->specialite = $specialite;
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

    public function getDateAffectation(): ?\DateTimeInterface
    {
        return $this->dateAffectation;
    }

    public function setDateAffectation(?\DateTimeInterface $date): static
    {
        $this->dateAffectation = $date;
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
     * @return Collection<int, Affectation>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): static
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations->add($affectation);
            $affectation->setPraticien($this);
        }
        return $this;
    }
}