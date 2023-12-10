<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Artist;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArtistFixtures extends Fixture
{
    public const DYA_RIKKU = "DyaRikku";

    public function __construct(private SluggerInterface $slugger)
    {
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $dyaRikku = new Artist;
        $dyaRikku->setName('DyaRikku')
        ->setSlug($this->slugger->slug($dyaRikku->getName())->lower()->toString())
        ->setDescription('2D Artist/Illustrator, Live2D Rigger, Vtuber. Where is my pink dog')
        ->setAvatar('build/images/avatar/DyaRikku_avatar.png')
        ->setTwitch('twitch.tv/dyarikku')
        ->setTwitter('twitter.com/dyarikku');
        $manager->persist($dyaRikku);
        $manager->flush();

        $this->addReference(self::DYA_RIKKU, $dyaRikku);
    }
}
