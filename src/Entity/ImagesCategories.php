<?php

namespace App\Entity;

use App\Repository\ImagesCategoriesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ImagesCategoriesRepository::class)]
#[UniqueEntity(fields: ['nom'], message: "Ce nom de catégorie d'images est déjà présent dans la base.")]
class ImagesCategories
{
 
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    #[Gedmo\Slug(fields: ["nom"])]
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
}
