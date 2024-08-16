<?php

namespace App\Entity;

use App\Repository\TagsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagsRepository::class)]
class Tags
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $icone = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $couleur = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $parent = null;

    /**
     * @var Collection<int, Galeries>
     */
    #[ORM\ManyToMany(targetEntity: Galeries::class, inversedBy: 'tags')]
    private Collection $galerie;

    public function __construct()
    {
        $this->galerie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(?string $icone): static
    {
        $this->icone = $icone;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function setParent(string $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, Galeries>
     */
    public function getGalerie(): Collection
    {
        return $this->galerie;
    }

    public function addGalerie(Galeries $galerie): static
    {
        if (!$this->galerie->contains($galerie)) {
            $this->galerie->add($galerie);
        }

        return $this;
    }

    public function removeGalerie(Galeries $galerie): static
    {
        $this->galerie->removeElement($galerie);

        return $this;
    }
}
