<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(
    fields: ["email"],
    message: "Invalid credentials."
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "Please enter an email.")]
    private string $email;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Please enter a username.")]
    private string $username;

    #[ORM\Column]
    private string $role;

    #[Assert\NotBlank(message: "Please enter a password.")]
    #[Assert\Length(
        min: 8,
        minMessage: "Password should have at least {{ limit }} caracters."
    )]
    #[Assert\Regex(
        "/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/",
        message: "Incorrect password format."
    )]
    private string $plainPassword;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private string $password;

    /** @var Collection<int, Artist> */
    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: Artist::class,
        orphanRemoval: true
    )]
    private Collection $artists;

    /** @var Collection<int, Category> */
    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: Category::class,
        orphanRemoval: true,
        cascade: ['remove']
    )]
    private Collection $categories;

    /** @var Collection<int, Image> */
    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: Image::class,
        orphanRemoval: true
    )]
    private Collection $images;

    /** @var Collection<int, Post> */
    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: Post::class,
        orphanRemoval: true
    )]
    private Collection $posts;

    /** @var Collection<int, Video> */
    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: Video::class,
        orphanRemoval: true
    )]
    private Collection $videos;

    /**
     * User constructor
     */
    public function __construct()
    {
        $this->role = 'ROLE_USER';
        $this->artists = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        assert(!empty($this->email), 'Email should not be empty.');
        return (string) $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [$this->role];
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Artist>
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): void
    {
        $artist->setUser($this);
        $this->artists->add($artist);
    }

    public function removeArtist(Artist $artist): void
    {
        $artist->setUser(null);
        $this->artists->removeElement($artist);
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): void
    {
        $category->setUser($this);
        $this->categories->add($category);
    }

    public function removeCategory(Category $category): void
    {
        $category->setUser(null);
        $this->categories->removeElement($category);
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): void
    {
        $image->setUser($this);
        $this->images->add($image);
    }

    public function removeImage(Image $image): void
    {
        $image->setUser(null);
        $this->images->removeElement($image);
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): void
    {
        $post->setUser($this);
        $this->posts->add($post);
    }

    public function removePost(Post $post): void
    {
        $post->setUser(null);
        $this->posts->removeElement($post);
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): void
    {
        $video->setUser($this);
        $this->videos->add($video);
    }

    public function removeVideo(Video $video): void
    {
        $video->setUser(null);
        $this->videos->removeElement($video);
    }
}
