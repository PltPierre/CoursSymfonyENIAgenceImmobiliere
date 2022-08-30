<?php

namespace App\Entity;

use App\Repository\BienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BienRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Bien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $avecJardin = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $urlImg = null;

    #[ORM\ManyToOne(inversedBy: 'biens')]
    private ?Agent $agent = null;

    #[ORM\ManyToMany(targetEntity: Acheteur::class, inversedBy: 'biens')]
    private Collection $acheteurs;

    #[ORM\Column]
    private ?int $metresCarres = null;

    #[ORM\Column(length: 30)]
    private ?string $etat = null;

    public function __construct()
    {
        $this->acheteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    #[ORM\PrePersist]
    public function setDateCreation(): self
    {
        $this->dateCreation = new \DateTimeImmutable();

        return $this;
    }

    public function isAvecJardin(): ?bool
    {
        return $this->avecJardin;
    }

    public function setAvecJardin(?bool $avecJardin): self
    {
        $this->avecJardin = $avecJardin;

        return $this;
    }

    public function getUrlImg(): ?string
    {
        return $this->urlImg;
    }

    public function setUrlImg(?string $urlImg): self
    {
        $this->urlImg = $urlImg;

        return $this;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * @return Collection<int, Acheteur>
     */
    public function getAcheteurs(): Collection
    {
        return $this->acheteurs;
    }

    public function addAcheteur(Acheteur $acheteur): self
    {
        if (!$this->acheteurs->contains($acheteur)) {
            $this->acheteurs->add($acheteur);
            $acheteur->addBien($this);
        }

        return $this;
    }

    public function removeAcheteur(Acheteur $acheteur): self
    {
        if ($this->acheteurs->removeElement($acheteur)) {
            $acheteur->removeBien($this);
        }

        return $this;
    }

    public function getMetresCarres(): ?int
    {
        return $this->metresCarres;
    }

    public function getPrixBien():?int{
        return $this->getMetresCarres() * ($this->getAgent()->isEstSenior() ? 2500 : 1500) + ($this->isAvecJardin() ? 10000 : 0) + $this->getMetresCarres() * ($this->getEtat() == 'excellent' ? 1500 : ($this->getEtat() == 'rafraichir' ? 1000 : 500));

    }

    public function setMetresCarres(int $metresCarres): self
    {
        $this->metresCarres = $metresCarres;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
