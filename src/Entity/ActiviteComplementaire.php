<?php

namespace App\Entity;

use App\Repository\ActiviteComplementaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ActiviteComplementaireRepository::class)]
#[ORM\Table(name: 'activite_complementaire')]
class ActiviteComplementaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idAC')]
    #[Groups(['activite:read'])]
    private ?int $id = null;

    #[ORM\Column(name: 'themeAC', length: 255)]
    #[Groups(['activite:read'])]
    private ?string $theme = null;

    #[ORM\Column(name: 'dateAC', type: 'date')]
    #[Groups(['activite:read'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(name: 'lieuAC', length: 255, nullable: true)]
    #[Groups(['activite:read'])]
    private ?string $lieu = null;

    #[ORM\ManyToMany(targetEntity: Praticien::class)]
    #[ORM\JoinTable(name: 'participer_praticien')]
    #[ORM\JoinColumn(name: 'idAC', referencedColumnName: 'idAC')]
    #[ORM\InverseJoinColumn(name: 'numeroSequentiel', referencedColumnName: 'numeroSequentiel')]
    #[ORM\InverseJoinColumn(name: 'idPraticien', referencedColumnName: 'idPraticien')]
    #[Groups(['activite:read'])]
    private Collection $praticiens;

    #[ORM\ManyToMany(targetEntity: Visiteur::class, inversedBy: 'activites')]
    #[ORM\JoinTable(name: 'participer_visiteur')]
    #[ORM\JoinColumn(name: 'idAC', referencedColumnName: 'idAC')]
    #[ORM\InverseJoinColumn(name: 'idVisiteur', referencedColumnName: 'idVisiteur')]
    #[Groups(['activite:read'])]
    private Collection $visiteurs;

    public function __construct()
    {
        $this->praticiens = new ArrayCollection();
        $this->visiteurs = new ArrayCollection();
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

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): static
    {
        $this->lieu = $lieu;
        return $this;
    }

    /**
     * @return Collection<int, Praticien>
     */
    public function getPraticiens(): Collection
    {
        return $this->praticiens;
    }

    public function addPraticien(Praticien $praticien): static
    {
        if (!$this->praticiens->contains($praticien)) {
            $this->praticiens->add($praticien);
        }
        return $this;
    }

    /**
     * @return Collection<int, Visiteur>
     */
    public function getVisiteurs(): Collection
    {
        return $this->visiteurs;
    }

    public function addVisiteur(Visiteur $visiteur): static
    {
        if (!$this->visiteurs->contains($visiteur)) {
            $this->visiteurs->add($visiteur);
        }
        return $this;
    }
}