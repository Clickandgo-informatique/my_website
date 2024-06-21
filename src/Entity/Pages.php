<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\SlugTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\PagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PagesRepository::class)]
class Pages
{
    use CreatedAtTrait,UpdatedAtTrait,SlugTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sous_titre = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\Column(length: 50)]
    private ?string $etat = null;

    /**
     * @var Collection<int, Galleries>
     */
    #[ORM\OneToMany(targetEntity: Galleries::class, mappedBy: 'page')]
    private Collection $galleries;

    public function __construct(){
        $this->created_at=new \DateTimeImmutable();
        $this->updated_at=new \DateTimeImmutable();
        $this->galleries = new ArrayCollection();               
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSousTitre(): ?string
    {
        return $this->sous_titre;
    }

    public function setSousTitre(?string $sous_titre): static
    {
        $this->sous_titre = $sous_titre;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): static
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, Galleries>
     */
    public function getGalleries(): Collection
    {
        return $this->galleries;
    }

    public function addGallery(Galleries $gallery): static
    {
        if (!$this->galleries->contains($gallery)) {
            $this->galleries->add($gallery);
            $gallery->setPage($this);
        }

        return $this;
    }

    public function removeGallery(Galleries $gallery): static
    {
        if ($this->galleries->removeElement($gallery)) {
            // set the owning side to null (unless already changed)
            if ($gallery->getPage() === $this) {
                $gallery->setPage(null);
            }
        }

        return $this;
    }
}
