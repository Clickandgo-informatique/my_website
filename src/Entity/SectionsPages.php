<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\SectionsPagesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: SectionsPagesRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['titre', 'page'], message: 'Cette page dispose déjà d\'une section avec ce titre, veuillez choisir un autre titre.')]
class SectionsPages
{
    use CreatedAtTrait, UpdatedAtTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\ManyToOne(inversedBy: 'sectionsPages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pages $page = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Galeries $galerie = null;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

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

    public function getGalerie(): ?Galeries
    {
        return $this->galerie;
    }

    public function setGalerie(?Galeries $galerie): static
    {
        $this->galerie = $galerie;

        return $this;
    }
}
