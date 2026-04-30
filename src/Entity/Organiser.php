<?php

namespace App\Entity;

use App\Repository\OrganiserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganiserRepository::class)]
#[ORM\Table(name: 'organiser')]
class Organiser
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Visiteur::class, inversedBy: 'organisations')]
    #[ORM\JoinColumn(name: 'idVisiteur', referencedColumnName: 'idVisiteur')]
    private ?Visiteur $visiteur = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Ac::class, inversedBy: 'organisations')]
    #[ORM\JoinColumn(name: 'idAC', referencedColumnName: 'idAC')]
    private ?Ac $ac = null;

    public function getVisiteur(): ?Visiteur { return $this->visiteur; }
    public function setVisiteur(?Visiteur $visiteur): static { $this->visiteur = $visiteur; return $this; }

    public function getAc(): ?Ac { return $this->ac; }
    public function setAc(?Ac $ac): static { $this->ac = $ac; return $this; }
}