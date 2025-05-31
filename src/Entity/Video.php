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
    /** @var int */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    /** @var string|null */
    #[ORM\Column(nullable: true, length: 255)]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'The name must be at least {{ limit }} characters.',
        maxMessage: 'The name cannot be longer than {{ limit }} characters.'
    )]
    private ?string $path = null;

    /** @var UploadedFile|null */
    #[Assert\File(
        maxSize: "2048M",
        mimeTypes: ['video/mp4', 'video/avi', 'video/mpeg', 'video/quicktime', 'video/mov', 'video/wmv'],
        mimeTypesMessage: 'Please upload a valid video (MP4, AVI, MPEG, QuickTime, MOV, WMV)',
    )]
    private ?UploadedFile $file = null;

    /** @var string|null */
    #[ORM\Column(nullable: true, length: 255)]
    #[Assert\Url(message: 'video.url.invalid')] // e.g translations/validators for error messages
    #[YoutubeUrl(message: 'video.url.not_youtube')] // e.g Validator/YoutubeUrlValidator
    private ?string $url = null;

    /** @var \DateTimeImmutable|null */
    #[ORM\Column(nullable: true)]
    #[Assert\NotNull(message: "Please select a date.")]
    #[Assert\LessThanOrEqual(
        "today",
        message: "The release date cannot be in the future."
    )]
    private ?\DateTimeImmutable $releasedThe = null;

    /** @var Collection<int, Post> */
    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'videos')]
    private Collection $posts;

    /** @var User|null */
    #[ORM\ManyToOne(inversedBy: 'videos')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?User $user = null;

    /** @var bool */
    #[ORM\Column(type: 'boolean')]
    private bool $isThumbnail = false;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /** @return int */
    public function getId(): int
    {
        return $this->id;
    }

    /** @return string|null */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     * @return static
     */
    public function setPath(?string $path): static
    {
        $this->path = $path;

        return $this;
    }

    /** @return UploadedFile|null */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @param UploadedFile|null $file
     * @return static
     */
    public function setFile(?UploadedFile $file): static
    {
        $this->file = $file;

        return $this;
    }

    /** @return string|null */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return static
     */
    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /** @return \DateTimeImmutable|null */
    public function getReleasedThe(): ?\DateTimeImmutable
    {
        return $this->releasedThe;
    }

    /**
     * @param \DateTimeImmutable|null $releasedThe
     * @return static
     */
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

    /**
     * @param Post $post
     * @return static
     */
    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->addVideo($this);
        }

        return $this;
    }

    /**
     * @param Post $post
     * @return static
     */
    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            $post->removeVideo($this);
        }

        return $this;
    }

    /** @return User|null */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return static
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /** @return bool */
    public function getIsThumbnail(): bool
    {
        return $this->isThumbnail;
    }

    /**
     * @param bool $isThumbnail
     * @return static
     */
    public function setIsThumbnail(bool $isThumbnail): self
    {
        $this->isThumbnail = $isThumbnail;
        return $this;
    }
}
