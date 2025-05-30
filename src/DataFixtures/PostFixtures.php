<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use App\Entity\User;
use App\DataFixtures\ArtistFixtures;
use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\ImageFixtures;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\VideoFixtures;
use App\Entity\Artist;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Video;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /* 
            1 post must have :
            1 or many artistes ;
            1 or many image(s)/video(s) ;
            1 or many categories ;
            1 user ;
            tags (not mandatory).
        */

        $userqdoe = $this->getReference(UserFixtures::USER_QDOE, User::class);

        for ($i = 1; $i <= 28; $i++) {
            $post = new Post();
            $post->setPostedAt(new \DateTimeImmutable())
            ->addArtist($this->getReference(ArtistFixtures::DYA_RIKKU, Artist::class))
            ->addCategory($this->getReference(CategoryFixtures::ILLUSTRATION, Category::class))
            ->addImage($this->getReference(ImageFixtures::IMG1, Image::class))
            ->setUser($userqdoe);
            $manager->persist($post);
        }

        $post1 = new Post();
        $post1->setPostedAt(new \DateTimeImmutable())
        ->addArtist($this->getReference(ArtistFixtures::DYA_RIKKU, Artist::class))
        ->addCategory($this->getReference(CategoryFixtures::ILLUSTRATION, Category::class))
        ->addImage($this->getReference(ImageFixtures::IMG1, Image::class))
        ->setUser($userqdoe);
        $manager->persist($post1);

        $post2 = new Post();
        $post2->setPostedAt(new \DateTimeImmutable())
        ->addArtist($this->getReference(ArtistFixtures::DYA_RIKKU, Artist::class))
        ->addCategory($this->getReference(CategoryFixtures::ILLUSTRATION, Category::class))
        ->addImage($this->getReference(ImageFixtures::IMG2, Image::class))
        ->setUser($userqdoe);
        $manager->persist($post2);

        $post3 = new Post();
        $post3->setPostedAt(new \DateTimeImmutable())
        ->addArtist($this->getReference(ArtistFixtures::WISHBONE, Artist::class))
        ->addCategory($this->getReference(CategoryFixtures::ILLUSTRATION, Category::class))
        ->addImage($this->getReference(ImageFixtures::IMG3, Image::class))
        ->setUser($userqdoe);
        $manager->persist($post3);

        $post4 = new Post();
        $post4->setPostedAt(new \DateTimeImmutable())
        ->addArtist($this->getReference(ArtistFixtures::YAYACHAN, Artist::class))
        ->addCategory($this->getReference(CategoryFixtures::LIVE2D, Category::class))
        ->addImage($this->getReference(ImageFixtures::IMG4, Image::class))
        ->addImage($this->getReference(ImageFixtures::IMG5, Image::class))
        ->addImage($this->getReference(ImageFixtures::IMG6, Image::class))
        ->addImage($this->getReference(ImageFixtures::IMG7, Image::class))
        ->setUser($userqdoe);
        $manager->persist($post4);

        $post5 = new Post();
        $post5->setPostedAt(new \DateTimeImmutable())
        ->addArtist($this->getReference(ArtistFixtures::KAVALLIERE, Artist::class))
        ->addCategory($this->getReference(CategoryFixtures::ANIMATION, Category::class))
        ->addVideo($this->getReference(VideoFixtures::VIDEO1, Video::class))
        ->setUser($userqdoe);
        $manager->persist($post5);

        $post6 = new Post();
        $post6->setPostedAt(new \DateTimeImmutable())
        ->addArtist($this->getReference(ArtistFixtures::KAVALLIERE, Artist::class))
        ->addCategory($this->getReference(CategoryFixtures::ANIMATION, Category::class))
        ->addImage($this->getReference(ImageFixtures::IMG3, Image::class))
        ->addImage($this->getReference(ImageFixtures::IMG8, Image::class))
        ->addImage($this->getReference(ImageFixtures::IMG1, Image::class))
        ->addImage($this->getReference(ImageFixtures::IMG2, Image::class))
        ->addImage($this->getReference(ImageFixtures::IMG9, Image::class))
        ->addVideo($this->getReference(VideoFixtures::VIDEO1, Video::class))
        ->addVideo($this->getReference(VideoFixtures::VIDEO2, Video::class))
        ->addVideo($this->getReference(VideoFixtures::VIDEO3, Video::class))
        ->addVideo($this->getReference(VideoFixtures::VIDEO4, Video::class))
        ->addVideo($this->getReference(VideoFixtures::VIDEO5, Video::class))
        ->setUser($userqdoe);
        $manager->persist($post6);

        $manager->flush();
    }

    // return an array of the fixture classes that must be loaded before this one
    /**
     * @return array<int, string>
     */
    public function getDependencies(): array
    {
        return [
            ArtistFixtures::class,
            CategoryFixtures::class,
            ImageFixtures::class,
            UserFixtures::class,
            VideoFixtures::class
        ];
    }
}
