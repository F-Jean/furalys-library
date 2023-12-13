<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use App\DataFixtures\ArtistFixtures;
use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\ImageFixtures;
use App\DataFixtures\VideoFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /* 
            1 post doit contenir :
            1 ou des artistes ;
            1 ou des image(s)/video(s) ;
            1 ou des catégories (exemple de catégories : animation, illustration, live2d) ;
            (1 utilisateur plus tard) ;
            (possiblement des tags plus tard).
        */

        $post1 = new Post();
        $post1->setPostedAt(new \DateTimeImmutable())
        ->addArtist($this->getReference(ArtistFixtures::DYA_RIKKU))
        ->addCategory($this->getReference(CategoryFixtures::ILLUSTRATION))
        ->addImage($this->getReference(ImageFixtures::IMG1));
        $manager->persist($post1);

        $post2 = new Post();
        $post2->setPostedAt(new \DateTimeImmutable())
        ->addArtist($this->getReference(ArtistFixtures::DYA_RIKKU))
        ->addCategory($this->getReference(CategoryFixtures::ILLUSTRATION))
        ->addImage($this->getReference(ImageFixtures::IMG2));
        $manager->persist($post2);

        $post3 = new Post();
        $post3->setPostedAt(new \DateTimeImmutable())
        ->addArtist($this->getReference(ArtistFixtures::WISHBONE))
        ->addCategory($this->getReference(CategoryFixtures::ILLUSTRATION))
        ->addImage($this->getReference(ImageFixtures::IMG3));
        $manager->persist($post3);

        $post4 = new Post();
        $post4->setPostedAt(new \DateTimeImmutable())
        ->addArtist($this->getReference(ArtistFixtures::YAYACHAN))
        ->addCategory($this->getReference(CategoryFixtures::LIVE2D))
        ->addImage($this->getReference(ImageFixtures::IMG4))
        ->addImage($this->getReference(ImageFixtures::IMG5))
        ->addImage($this->getReference(ImageFixtures::IMG6))
        ->addImage($this->getReference(ImageFixtures::IMG7));
        $manager->persist($post4);

        $post5 = new Post();
        $post5->setPostedAt(new \DateTimeImmutable())
        ->addArtist($this->getReference(ArtistFixtures::KAVALLIERE))
        ->addCategory($this->getReference(CategoryFixtures::ANIMATION))
        ->addVideo($this->getReference(VideoFixtures::VIDEO1));
        $manager->persist($post5);

        $manager->flush();
    }

    // return an array of the fixture classes that must be loaded before this one, here ArtistFixtures
    /**
     * @return array<int, string>
     */
    public function getDependencies(): array
    {
        return [
            ArtistFixtures::class,
            CategoryFixtures::class,
            ImageFixtures::class,
            VideoFixtures::class
        ];
    }
}
