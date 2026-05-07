<?php

namespace App\Entity;

use App\Repository\AcRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AcRepository::class)]
#[ORM\Table(name: 'ac')]
class Ac
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idAC')]
    #[Groups(['ac:read'])]
    private ?int $idAC = null;

    #[ORM\Column(name: 'themeAC', length: 50, nullable: true)]
    #[Groups(['ac:read'])]
    private ?string $themeAC = null;

    #[ORM\Column(name: 'dateAC', type: 'date', nullable: true)]
    #[Groups(['ac:read'])]
    private ?\DateTimeInterface $dateAC = null;

    #[ORM\Column(name: 'lieuAC', length: 50, nullable: true)]
    #[Groups(['ac:read'])]
    private ?string $lieuAC = null;

    #[ORM\OneToMany(mappedBy: 'ac', targetEntity: Participer::class)]
    private Collection $participations;

    #[ORM\OneToMany(mappedBy: 'ac', targetEntity: Organiser::class)]
    private Collection $organisations;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
        $this->organisations = new ArrayCollection();
    }

    public function getIdAC(): ?int { return $this->idAC; }
    public function setIdAC(int $idAC): static { $this->idAC = $idAC; return $this; }

    public function getThemeAC(): ?string { return $this->themeAC; }
    public function setThemeAC(?string $themeAC): static { $this->themeAC = $themeAC; return $this; }

    public function getDateAC(): ?\DateTimeInterface { return $this->dateAC; }
    public function setDateAC(?\DateTimeInterface $dateAC): static { $this->dateAC = $dateAC; return $this; }

    public function getLieuAC(): ?string { return $this->lieuAC; }
    public function setLieuAC(?string $lieuAC): static { $this->lieuAC = $lieuAC; return $this; }

    /** @return Collection<int, Participer> */
    public function getParticipations(): Collection { return $this->participations; }

    /** @return Collection<int, Organiser> */
    public function getOrganisations(): Collection { return $this->organisations; }
}