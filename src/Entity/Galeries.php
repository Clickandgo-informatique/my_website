<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\GaleriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\AST\Functions\LengthFunction;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: GaleriesRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['titre'], message: "Ce titre de galerie d'images existe déjà dans la base, veuillez en choisir un autre.")]
class Galeries
{
    use CreatedAtTrait, UpdatedAtTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column()]
    private ?bool $is_active = null;

    #[ORM\ManyToOne(inversedBy: 'galeries')]
    private ?Pages $page = null;

    /**
     * @var Collection<int, Images>
     */
    #[ORM\OneToMany(targetEntity: Images::class, mappedBy: 'galerie', orphanRemoval: true, cascade: ['persist'])]
    private Collection $images;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $primary_background_color = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $primary_title_color = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Tags>
     */
    #[ORM\ManyToMany(targetEntity: Tags::class, mappedBy: 'galerie')]
    private Collection $tags;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable;
        $this->updated_at = new \DateTimeImmutable;
        $this->images = new ArrayCollection();
        // $this->is_active = false;
        $this->tags = new ArrayCollection();
    }
    public function getDetailsGalerie(): string
    {
        return $this->getTitre() . ' [ type : '. $this->getType() . '] - images : ' . count($this->images);
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    public function setActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getPage(): ?Pages
    {
        return $this->page;
    }

    public function setPage(?Pages $page): static
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setGalerie($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getGalerie() === $this) {
                $image->setGalerie(null);
            }
        }

        return $this;
    }

    public function getPrimaryBackgroundColor(): ?string
    {
        return $this->primary_background_color;
    }

    public function setPrimaryBackgroundColor(?string $primary_background_color): static
    {
        $this->primary_background_color = $primary_background_color;

        return $this;
    }

    public function getPrimaryTitleColor(): ?string
    {
        return $this->primary_title_color;
    }

    public function setPrimaryTitleColor(?string $primary_title_color): static
    {
        $this->primary_title_color = $primary_title_color;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Tags>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addGalerie($this);
        }

        return $this;
    }

    public function removeTag(Tags $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeGalerie($this);
        }

        return $this;
    }
}
