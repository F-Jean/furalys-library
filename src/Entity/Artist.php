<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
#[UniqueEntity(
    fields: ["name"],
    message: "This artist already exists."
)]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "Please enter a name.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'The name must be at least {{ limit }} characters long.',
        maxMessage: 'The name cannot be longer than {{ limit }} characters.'
    )]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $slug;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Assert\Length(
        max: 1000,
        maxMessage: 'The description cannot be longer than {{ limit }} characters.'
    )]    
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $debut;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $end;

    #[ORM\Column(length: 255)]
    private string $avatar;

    #[Assert\NotBlank(message: "Please provide an image.")]
    #[Assert\File(
        mimeTypes:["image/*"],
        mimeTypesMessage: "Please upload a valid image file."
    )]
    private UploadedFile $avatarFile;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(
        pattern: '/twitch\.tv/i',
        match: false,
        message: 'Please enter only the Twitch username, not the full URL.'
    )]
    private ?string $twitch = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(
        pattern: '/twitter\.com/i',
        match: false,
        message: 'Please enter only the Twitter username, not the full URL.'
    )]
    private ?string $twitter = null;

    /** @var Collection<int, Post> */
    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'artists')]
    private Collection $posts;

    #[ORM\ManyToOne(inversedBy: 'artists')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    #[Assert\NotNull(message: 'A user is required.')]
    private ?User $user;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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

    public function getDebut(): ?\DateTimeImmutable
    {
        return $this->debut;
    }

    public function setDebut(?\DateTimeImmutable $debut): static
    {
        $this->debut = $debut;

        return $this;
    }

    public function getEnd(): ?\DateTimeImmutable
    {
        return $this->end;
    }

    public function setEnd(?\DateTimeImmutable $end): static
    {
        $this->end = $end;

        return $this;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAvatarFile(): UploadedFile
    {
        return $this->avatarFile;
    }

    public function setAvatarFile(UploadedFile $avatarFile): static
    {
        $this->avatarFile = $avatarFile;

        return $this;
    }

    public function getTwitch(): ?string
    {
        return $this->twitch;
    }

    public function setTwitch(?string $twitch): static
    {
        $this->twitch = $twitch;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): static
    {
        $this->twitter = $twitter;

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
            $post->addArtist($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            $post->removeArtist($this);
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
