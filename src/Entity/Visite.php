<?php

namespace App\Entity;

use App\Repository\VisiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VisiteRepository::class)]
#[ORM\Table(name: 'visite')]
class Visite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idVisite')]
    #[Groups(['visite:read', 'visite:list'])]
    private ?int $id = null;

    #[ORM\Column(name: 'dateVisite', type: Types::DATE_MUTABLE)]
    #[Groups(['visite:read', 'visite:list'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(name: 'motifVisite', length: 255)]
    #[Groups(['visite:read', 'visite:list'])]
    private ?string $motif = null;

    #[ORM\Column(name: 'bilanVisite', type: Types::TEXT, nullable: true)]
    #[Groups(['visite:read'])]
    private ?string $bilan = null;

    #[ORM\Column(name: 'compteRenduVisite', length: 500, nullable: true)]
    #[Groups(['visite:read'])]
    private ?string $compteRendu = null;

    #[ORM\ManyToOne(targetEntity: Visiteur::class, inversedBy: 'visites')]
    #[ORM\JoinColumn(name: 'idVisiteur', referencedColumnName: 'idVisiteur')]
    #[Groups(['visite:read'])]
    private ?Visiteur $visiteur = null;

    #[ORM\ManyToOne(targetEntity: Praticien::class, inversedBy: 'visites')]
    #[ORM\JoinColumn(name: 'numeroSequentiel', referencedColumnName: 'numeroSequentiel')]
    #[ORM\JoinColumn(name: 'idPraticien', referencedColumnName: 'idPraticien')]
    #[Groups(['visite:read', 'visite:list'])]
    private ?Praticien $praticien = null;

    #[ORM\OneToMany(mappedBy: 'visite', targetEntity: Echantillon::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[Groups(['visite:read'])]
    private Collection $echantillons;

    public function __construct()
    {
        $this->echantillons = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): static
    {
        $this->motif = $motif;
        return $this;
    }

    public function getBilan(): ?string
    {
        return $this->bilan;
    }

    public function setBilan(?string $bilan): static
    {
        $this->bilan = $bilan;
        return $this;
    }

    public function getCompteRendu(): ?string
    {
        return $this->compteRendu;
    }

    public function setCompteRendu(?string $compteRendu): static
    {
        $this->compteRendu = $compteRendu;
        return $this;
    }

    public function getVisiteur(): ?Visiteur
    {
        return $this->visiteur;
    }

    public function setVisiteur(?Visiteur $visiteur): static
    {
        $this->visiteur = $visiteur;
        return $this;
    }

    public function getPraticien(): ?Praticien
    {
        return $this->praticien;
    }

    public function setPraticien(?Praticien $praticien): static
    {
        $this->praticien = $praticien;
        return $this;
    }

    /**
     * @return Collection<int, Echantillon>
     */
    public function getEchantillons(): Collection
    {
        return $this->echantillons;
    }

    public function addEchantillon(Echantillon $echantillon): static
    {
        if (!$this->echantillons->contains($echantillon)) {
            $this->echantillons->add($echantillon);
            $echantillon->setVisite($this);
        }
        return $this;
    }

    public function removeEchantillon(Echantillon $echantillon): static
    {
        if ($this->echantillons->removeElement($echantillon)) {
            if ($echantillon->getVisite() === $this) {
                $echantillon->setVisite(null);
            }
        }
        return $this;
    }

    public function clearEchantillons(): static
    {
        foreach ($this->echantillons as $echantillon) {
            $this->removeEchantillon($echantillon);
        }
        return $this;
    }
}