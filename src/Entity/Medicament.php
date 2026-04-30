<?php

namespace App\Entity;

use App\Repository\MedicamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MedicamentRepository::class)]
#[ORM\Table(name: 'medicament')]
class Medicament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idMedicament')]
    #[Groups(['medicament:read', 'visite:read'])]
    private ?int $id = null;

    #[ORM\Column(name: 'libelleMedicament', length: 255)]
    #[Groups(['medicament:read', 'visite:read'])]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'medicament', targetEntity: Echantillon::class, orphanRemoval: true)]
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
     * @return Collection<int, Echantillon>
     */
    public function getEchantillons(): Collection
    {
        return $this->echantillons;
    }
}