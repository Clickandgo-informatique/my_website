<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\PagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: PagesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Pages
{
    use CreatedAtTrait, UpdatedAtTrait;
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

    #[Gedmo\Slug(fields: ["titre"])]
    #[ORM\Column(type: 'string', length: 255)]

    private $slug;

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @var Collection<int, Galleries>
     */
    #[ORM\OneToMany(targetEntity: Galeries::class, mappedBy: 'page')]
    private Collection $galeries;

    /**
     * @var Collection<int, SectionsPages>
     */
    #[ORM\OneToMany(targetEntity: SectionsPages::class, mappedBy: 'page',cascade:['persist'])]
    private Collection $sectionsPages;

    #[ORM\Column(nullable: true)]
    private ?bool $is_page_accueil = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $icone_onglet = null;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();       
        $this->sectionsPages = new ArrayCollection();
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
     * @return Collection<int, SectionsPages>
     */
    public function getSectionsPages(): Collection
    {
        return $this->sectionsPages;
    }

    public function addSectionsPage(SectionsPages $sectionsPage): static
    {
        if (!$this->sectionsPages->contains($sectionsPage)) {
            $this->sectionsPages->add($sectionsPage);
            $sectionsPage->setPage($this);
        }

        return $this;
    }

    public function removeSectionsPage(SectionsPages $sectionsPage): static
    {
        if ($this->sectionsPages->removeElement($sectionsPage)) {
            // set the owning side to null (unless already changed)
            if ($sectionsPage->getPage() === $this) {
                $sectionsPage->setPage(null);
            }
        }

        return $this;
    }

    public function isPageAccueil(): ?bool
    {
        return $this->is_page_accueil;
    }

    public function setIsPageAccueil(?bool $is_page_accueil): static
    {
        $this->is_page_accueil = $is_page_accueil;

        return $this;
    }

    public function getIconeOnglet(): ?string
    {
        return $this->icone_onglet;
    }

    public function setIconeOnglet(?string $icone_onglet): static
    {
        $this->icone_onglet = $icone_onglet;

        return $this;
    }
}
