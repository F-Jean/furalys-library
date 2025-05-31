<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\UniqueThumbnail;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[UniqueThumbnail]
class Post
{
    /** @var int */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    /** @var \DateTimeImmutable */
    #[ORM\Column]
    private \DateTimeImmutable $postedAt;

    /** @var Collection<int, Artist> */
    #[ORM\ManyToMany(targetEntity: Artist::class, inversedBy: 'posts')]
    private Collection $artists;

    /** @var Collection<int, Category> */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'posts')]
    #[Assert\NotBlank(
        message: "You must select at least one category"
    )]
    private Collection $categories;

    /** @var Collection<int, Image> */
    #[ORM\ManyToMany(
        targetEntity: Image::class,
        inversedBy: 'posts',
        cascade: ['persist']
    )]
    #[Assert\Valid]
    #[Assert\Count(
        max: 5,
        maxMessage: 'Only upload up to {{ limit }} images.'
    )]
    private Collection $images;

    /** @var Collection<int, Video> */
    #[ORM\ManyToMany(targetEntity: Video::class, inversedBy: 'posts', cascade: ['persist'])]
    #[Assert\Valid]
    #[Assert\Count(
        max: 5,
        maxMessage: 'Only upload up to {{ limit }} videos.'
    )]
    private Collection $videos;

    /** @var User|null */
    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    public function __construct()
    {
        $this->postedAt = new \DateTimeImmutable();
        $this->artists = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    /** @return int */
    public function getId(): int
    {
        return $this->id;
    }

    /** @return \DateTimeImmutable */
    public function getPostedAt(): \DateTimeImmutable
    {
        return $this->postedAt;
    }

    /**
     * @param \DateTimeImmutable $postedAt
     * @return static
     */
    public function setPostedAt(\DateTimeImmutable $postedAt): static
    {
        $this->postedAt = $postedAt;

        return $this;
    }

    /**
     * @return Collection<int, Artist>
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    /**
     * @param Artist $artist
     * @return static
     */
    public function addArtist(Artist $artist): static
    {
        if (!$this->artists->contains($artist)) {
            $this->artists->add($artist);
        }

        return $this;
    }

    /**
     * @param Artist $artist
     * @return static
     */
    public function removeArtist(Artist $artist): static
    {
        $this->artists->removeElement($artist);

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param Category $category
     * @return static
     */
    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addPost($this);
        }

        return $this;
    }

    /**
     * @param Category $category
     * @return static
     */
    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removePost($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param Image $image
     * @return static
     */
    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
        }

        return $this;
    }

    /**
     * @param Image $image
     * @return static
     */
    public function removeImage(Image $image): static
    {
        $this->images->removeElement($image);

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    /**
     * @param Video $video
     * @return static
     */
    public function addVideo(Video $video): static
    {
        if (!$this->videos->contains($video)) {
            $this->videos->add($video);
        }

        return $this;
    }

    /**
     * @param Video $video
     * @return static
     */
    public function removeVideo(Video $video): static
    {
        $this->videos->removeElement($video);

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
}
