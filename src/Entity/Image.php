<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[UniqueEntity(
    fields: ["file"],
    message: "Cette image existe déjà !"
)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(nullable: true, length: 255)]
    private ?string $path = null;

    #[Assert\File(
        maxSize: "300M",
        mimeTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/pdf', 'image/ai', 'image/psd', 'image/clip', 'image/svg'],
        mimeTypesMessage: 'Please upload a valid image (JPEG, JPG, PNG, GIF, PDF, AI, PSD, CLIP, SVG)',
    )]
    private ?UploadedFile $file = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $releasedThe = null;

    /** @var Collection<int, Post> */
    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'images')]
    private Collection $posts;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?User $user = null;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getReleasedThe(): ?\DateTimeImmutable
    {
        return $this->releasedThe;
    }

    public function setReleasedThe(?\DateTimeImmutable $releasedThe): static
    {
        $this->releasedThe = $releasedThe;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->addImage($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            $post->removeImage($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
