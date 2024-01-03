<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column(length: 255)]
    private string $username;

    #[ORM\Column]
    private string $role;

    private string $plainPassword;

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

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private string $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Artist::class, orphanRemoval: true)]
    private Collection $artists;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Category::class, orphanRemoval: true)]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Image::class, orphanRemoval: true)]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class, orphanRemoval: true)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Video::class, orphanRemoval: true)]
    private Collection $videos;

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
