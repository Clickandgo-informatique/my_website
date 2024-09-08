<?php

namespace App\Entity;

use App\Repository\BlogCategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogCategoriesRepository::class)]
class BlogCategories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(length: 100)]
    private ?string $slug = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'blogCategories')]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $blogCategories;

    /**
     * @var Collection<int, Posts>
     */
    #[ORM\ManyToMany(targetEntity: Posts::class, mappedBy: 'blog_categories')]
    private Collection $posts;

    public function __construct()
    {
        $this->blogCategories = new ArrayCollection();
        $this->posts = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getBlogCategories(): Collection
    {
        return $this->blogCategories;
    }

    public function addBlogCategory(self $blogCategory): static
    {
        if (!$this->blogCategories->contains($blogCategory)) {
            $this->blogCategories->add($blogCategory);
            $blogCategory->setParent($this);
        }

        return $this;
    }

    public function removeBlogCategory(self $blogCategory): static
    {
        if ($this->blogCategories->removeElement($blogCategory)) {
            // set the owning side to null (unless already changed)
            if ($blogCategory->getParent() === $this) {
                $blogCategory->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Posts>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Posts $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->addBlogCategory($this);
        }

        return $this;
    }

    public function removePost(Posts $post): static
    {
        if ($this->posts->removeElement($post)) {
            $post->removeBlogCategory($this);
        }

        return $this;
    }
}
