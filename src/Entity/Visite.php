<?php

namespace App\Entity;

use App\Repository\VisiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

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

    #[ORM\Column(name: 'motifVisite', length: 200)]
    #[Groups(['visite:read', 'visite:list'])]
    private ?string $motif = null;

    #[ORM\Column(name: 'bilanVisite', length: 300, nullable: true)]
    #[Groups(['visite:read', 'visite:list'])]
    private ?string $visite = null;

    #[ORM\Column(name: 'compteRenduVisite', length: 100, nullable: true)]
    #[Groups(['visite:read', 'visite:list'])]
    private ?string $compteRendu = null;

    #[ORM\ManyToOne(targetEntity: Visiteur::class, inversedBy: 'visites')]
    #[ORM\JoinColumn(name: 'idVisiteur', referencedColumnName: 'idVisiteur')]
    #[Groups(['visite:read', 'visite:list'])]
    private ?Visiteur $visiteur = null;

    #[ORM\ManyToOne(targetEntity: Praticien::class, inversedBy: 'visites')]
    #[ORM\JoinColumn(name: 'praticien_id', referencedColumnName: 'idPraticien', nullable: false)]
    #[Groups(['visite:read', 'visite:list'])]
    private ?Praticien $praticien = null;

    #[ORM\OneToMany(mappedBy: 'visite', targetEntity: Proposer::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $propositions;

    public function __construct()
    {
        $this->propositions = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): static { $this->id = $id; return $this; }

    public function getDate(): ?\DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $date): static { $this->date = $date; return $this; }

    public function getMotif(): ?string { return $this->motif; }
    public function setMotif(string $motif): static { $this->motif = $motif; return $this; }

    public function getBilan(): ?string { return $this->visite; }
    public function setBilan(?string $visite): static { $this->visite = $visite; return $this; }

    public function getCompteRendu(): ?string { return $this->compteRendu; }
    public function setCompteRendu(?string $compteRendu): static { $this->compteRendu = $compteRendu; return $this; }

    public function getVisiteur(): ?Visiteur { return $this->visiteur; }
    public function setVisiteur(?Visiteur $visiteur): static { $this->visiteur = $visiteur; return $this; }

    public function getPraticien(): ?Praticien { return $this->praticien; }
    public function setPraticien(?Praticien $praticien): static { $this->praticien = $praticien; return $this; }

    /** @return Collection<int, Proposer> */
    public function getPropositions(): Collection { return $this->propositions; }

    public function addProposition(Proposer $proposition): static
    {
        if (!$this->propositions->contains($proposition)) {
            $this->propositions->add($proposition);
            $proposition->setVisite($this);
        }
        return $this;
    }

    public function removeProposition(Proposer $proposition): static
    {
        if ($this->propositions->removeElement($proposition)) {
            if ($proposition->getVisite() === $this) {
                $proposition->setVisite(null);
            }
        }
        return $this;
    }

    public function clearPropositions(): static
    {
        foreach ($this->propositions as $proposition) {
            $this->removeProposition($proposition);
        }
        return $this;
    }
}