<?php

namespace App\Entity;

use App\Repository\SpecialiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SpecialiteRepository::class)]
#[ORM\Table(name: 'specialite')]
class Specialite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['specialite:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['specialite:read', 'praticien:read'])]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'specialite', targetEntity: Praticien::class)]
    private Collection $praticiens;

    public function __construct()
    {
        $this->praticiens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Collection<int, Praticien>
     */
    public function getPraticiens(): Collection
    {
        return $this->praticiens;
    }
}