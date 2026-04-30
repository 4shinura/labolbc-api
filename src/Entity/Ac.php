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
    private ?int $id = null;

    #[ORM\Column(name: 'themeAC', length: 50, nullable: true)]
    #[Groups(['ac:read'])]
    private ?string $theme = null;

    #[ORM\Column(name: 'dateAC', type: 'date', nullable: true)]
    #[Groups(['ac:read'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(name: 'lieuAC', length: 50, nullable: true)]
    #[Groups(['ac:read'])]
    private ?string $lieu = null;

    #[ORM\OneToMany(mappedBy: 'ac', targetEntity: Participer::class)]
    private Collection $participations;

    #[ORM\OneToMany(mappedBy: 'ac', targetEntity: Organiser::class)]
    private Collection $organisations;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
        $this->organisations = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): static { $this->id = $id; return $this; }

    public function getTheme(): ?string { return $this->theme; }
    public function setTheme(?string $theme): static { $this->theme = $theme; return $this; }

    public function getDate(): ?\DateTimeInterface { return $this->date; }
    public function setDate(?\DateTimeInterface $date): static { $this->date = $date; return $this; }

    public function getLieu(): ?string { return $this->lieu; }
    public function setLieu(?string $lieu): static { $this->lieu = $lieu; return $this; }

    /** @return Collection<int, Participer> */
    public function getParticipations(): Collection { return $this->participations; }

    /** @return Collection<int, Organiser> */
    public function getOrganisations(): Collection { return $this->organisations; }
}