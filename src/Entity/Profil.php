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
    private ?int $id = null;

    #[ORM\Column(name: 'username', length: 50)]
    #[Groups(['profil:read'])]
    private ?string $username = null;

    #[ORM\Column(name: 'password', length: 100)]
    private ?string $password = null;

    #[ORM\Column(name: 'typeProfil', length: 50)]
    #[Groups(['profil:read'])]
    private ?string $usertype = null;

    #[ORM\OneToOne(mappedBy: 'profil', targetEntity: Visiteur::class)]
    private ?Visiteur $visiteur = null;

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): static { $this->id = $id; return $this; }

    public function getUsername(): ?string { return $this->username; }
    public function setUsername(string $username): static { $this->username = $username; return $this; }

    public function getPassword(): ?string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }

    public function getUsertype(): ?string { return $this->usertype; }
    public function setUsertype(string $usertype): static { $this->usertype = $usertype; return $this; }

    public function getVisiteur(): ?Visiteur { return $this->visiteur; }
    public function setVisiteur(?Visiteur $visiteur): static { $this->visiteur = $visiteur; return $this; }
}