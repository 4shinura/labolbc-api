<?php

namespace App\Entity;

use App\Repository\ProfilRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProfilRepository::class)]
#[ORM\Table(name: 'profil')]
class Profil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idProfil')]
    #[Groups(['profil:read'])]
    private ?int $idProfil = null;

    #[ORM\Column(name: 'email', length: 100)]
    #[Groups(['profil:read'])]
    private ?string $email = null;

    #[ORM\Column(name: 'password', length: 100, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(name: 'typeProfil', length: 50, nullable: true)]
    #[Groups(['profil:read'])]
    private ?string $typeProfil = null;

    #[ORM\OneToOne(mappedBy: 'profil', targetEntity: Visiteur::class)]
    private ?Visiteur $visiteur = null;

    public function getIdProfil(): ?int { return $this->idProfil; }
    public function setIdProfil(int $idProfil): static { $this->idProfil = $idProfil; return $this; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }

    public function getPassword(): ?string { return $this->password; }
    public function setPassword(?string $password): static { $this->password = $password; return $this; }

    public function getTypeProfil(): ?string { return $this->typeProfil; }
    public function setTypeProfil(string $typeProfil): static { $this->typeProfil = $typeProfil; return $this; }

    public function getVisiteur(): ?Visiteur { return $this->visiteur; }
    public function setVisiteur(?Visiteur $visiteur): static { $this->visiteur = $visiteur; return $this; }
}