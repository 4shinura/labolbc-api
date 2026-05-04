<?php

namespace App\Entity;

use App\Repository\RepertorierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepertorierRepository::class)]
#[ORM\Table(name: 'repertorier')]
class Repertorier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Praticien::class, inversedBy: 'repertories')]
    #[ORM\JoinColumn(name: 'praticien_id', referencedColumnName: 'id', nullable: false)]
    private ?Praticien $praticien = null;

    #[ORM\ManyToOne(targetEntity: Visiteur::class, inversedBy: 'repertories')]
    #[ORM\JoinColumn(name: 'visiteur_id', referencedColumnName: 'idVisiteur', nullable: false)]
    private ?Visiteur $visiteur = null;

    public function getId(): ?int { return $this->id; }

    public function getPraticien(): ?Praticien { return $this->praticien; }
    public function setPraticien(?Praticien $praticien): static { $this->praticien = $praticien; return $this; }

    public function getVisiteur(): ?Visiteur { return $this->visiteur; }
    public function setVisiteur(?Visiteur $visiteur): static { $this->visiteur = $visiteur; return $this; }
}