<?php

namespace App\Entity;

use App\Repository\EchantillonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EchantillonRepository::class)]
#[ORM\Table(name: 'echantillon')]
class Echantillon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Visite::class, inversedBy: 'echantillons')]
    #[ORM\JoinColumn(name: 'idVisite', referencedColumnName: 'idVisite')]
    private ?Visite $visite = null;

    #[ORM\ManyToOne(targetEntity: Medicament::class, inversedBy: 'echantillons')]
    #[ORM\JoinColumn(name: 'idMedicament', referencedColumnName: 'idMedicament')]
    #[Groups(['visite:read'])]
    private ?Medicament $medicament = null;

    #[ORM\Column]
    #[Groups(['visite:read'])]
    private ?int $quantite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisite(): ?Visite
    {
        return $this->visite;
    }

    public function setVisite(?Visite $visite): static
    {
        $this->visite = $visite;
        return $this;
    }

    public function getMedicament(): ?Medicament
    {
        return $this->medicament;
    }

    public function setMedicament(?Medicament $medicament): static
    {
        $this->medicament = $medicament;
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }
}