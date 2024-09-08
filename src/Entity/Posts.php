<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column(length: 255)]
    private ?string $featuredImage = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    /**
     * @var Collection<int, BlogCategories>
     */
    #[ORM\ManyToMany(targetEntity: BlogCategories::class, inversedBy: 'posts')]
    private Collection $blog_categories;

    /**
     * @var Collection<int, BlogTags>
     */
    #[ORM\ManyToMany(targetEntity: BlogTags::class, inversedBy: 'posts')]
    private Collection $tags;

    /**
     * @var Collection<int, Comments>
     */
    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: 'posts', orphanRemoval: true)]
    private Collection $comments;

    public function __construct()
    {
        $this->blog_categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getFeaturedImage(): ?string
    {
        return $this->featuredImage;
    }

    public function setFeaturedImage(string $featuredImage): static
    {
        $this->featuredImage = $featuredImage;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection<int, BlogCategories>
     */
    public function getBlogCategories(): Collection
    {
        return $this->blog_categories;
    }

    public function addBlogCategory(BlogCategories $blogCategory): static
    {
        if (!$this->blog_categories->contains($blogCategory)) {
            $this->blog_categories->add($blogCategory);
        }

        return $this;
    }

    public function removeBlogCategory(BlogCategories $blogCategory): static
    {
        $this->blog_categories->removeElement($blogCategory);

        return $this;
    }

    /**
     * @return Collection<int, BlogTags>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(BlogTags $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(BlogTags $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPosts($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPosts() === $this) {
                $comment->setPosts(null);
            }
        }

        return $this;
    }
}
