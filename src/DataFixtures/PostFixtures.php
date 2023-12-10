<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use App\DataFixtures\ArtistFixtures;
use App\DataFixtures\CategoryFixtures;
use App\Entity\Image;
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
        ->addImage($this->getReference(ImageFixtures::POST1));
        $manager->persist($post1);

        $post2 = new Post();
        $post2->setPostedAt(new \DateTimeImmutable())
        ->addArtist($this->getReference(ArtistFixtures::DYA_RIKKU))
        ->addCategory($this->getReference(CategoryFixtures::ILLUSTRATION))
        ->addImage($this->getReference(ImageFixtures::POST2));
        $manager->persist($post2);

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
        ];
    }
}
