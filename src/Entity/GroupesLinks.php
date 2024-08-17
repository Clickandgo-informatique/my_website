<?php

namespace App\Entity;

use App\Repository\GroupesLinksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupesLinksRepository::class)]
class GroupesLinks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column]
    private ?bool $afficher_titre = null;

    /**
     * @var Collection<int, Links>
     */
    #[ORM\OneToMany(targetEntity: Links::class, mappedBy: 'groupe')]
    private Collection $links;

    #[ORM\Column(length: 15)]
    private ?string $parent = null;

    public function __construct()
    {
        $this->links = new ArrayCollection();
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

    public function isAfficherTitre(): ?bool
    {
        return $this->afficher_titre;
    }

    public function setAfficherTitre(bool $afficher_titre): static
    {
        $this->afficher_titre = $afficher_titre;

        return $this;
    }

    /**
     * @return Collection<int, Links>
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(Links $link): static
    {
        if (!$this->links->contains($link)) {
            $this->links->add($link);
            $link->setGroupe($this);
        }

        return $this;
    }

    public function removeLink(Links $link): static
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getGroupe() === $this) {
                $link->setGroupe(null);
            }
        }

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
}
