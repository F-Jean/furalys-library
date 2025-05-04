<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\YoutubeUrl;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(nullable: true, length: 255)]
    private ?string $path = null;

    #[Assert\File(
        maxSize: "2048M",
        mimeTypes: ['video/mp4', 'video/avi', 'video/mpeg', 'video/quicktime', 'video/mov', 'video/wmv'],
        mimeTypesMessage: 'Please upload a valid video (MP4, AVI, MPEG, QuickTime, MOV, WMV)',
    )]
    private ?UploadedFile $file = null;

    #[ORM\Column(nullable: true, length: 255)]
    #[Assert\Url(message: 'video.url.invalid')] // e.g translations/validators for error messages
    #[YoutubeUrl(message: 'video.url.not_youtube')] // e.g Validator/YoutubeUrlValidator
    private ?string $url = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $releasedThe = null;

    /** @var Collection<int, Post> */
    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'videos')]
    private Collection $posts;

    #[ORM\ManyToOne(inversedBy: 'videos')]
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

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
            $post->addVideo($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            $post->removeVideo($this);
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
