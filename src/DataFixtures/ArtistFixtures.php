<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Artist;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArtistFixtures extends Fixture
{
    public const DYA_RIKKU = "DyaRikku";
    public const KAVALLIERE = "Kavalliere";
    public const WISHBONE = "wishbone777";
    public const YAYACHAN = "YayaChan";

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

        $wishbone777 = new Artist;
        $wishbone777->setName('wishbone777')
        ->setSlug($this->slugger->slug($wishbone777->getName())->lower()->toString())
        ->setDescription('You can call me ゆう。or  wishbone')
        ->setAvatar('build/images/avatar/wishbone777_avatar.png')
        ->setTwitter('twitter.com/wishbone777');
        $manager->persist($wishbone777);

        $kavalliere = new Artist;
        $kavalliere->setName('Kavalliere')
        ->setSlug($this->slugger->slug($kavalliere->getName())->lower()->toString())
        ->setDescription('𝘝𝘛𝘶𝘣𝘦𝘳 𝘔𝘢𝘮𝘢 𝘢𝘯𝘥 𝘚𝘵𝘳𝘦𝘢𝘮𝘦𝘳 // The Lady of Fukurou Sanctuary')
        ->setAvatar('build/images/avatar/kavalliere_avatar.png')
        ->setTwitch('twitch.tv/kavalliere')
        ->setTwitter('twitter.com/Kavalliere_');
        $manager->persist($kavalliere);

        $YayaChan = new Artist;
        $YayaChan->setName('YayaChan')
        ->setSlug($this->slugger->slug($YayaChan->getName())->lower()->toString())
        ->setDescription('イラストレーター')
        ->setAvatar('build/images/avatar/YayaChan_avatar.png')
        ->setTwitter('twitter.com/YayaChanArtist');
        $manager->persist($YayaChan);

        $manager->flush();

        $this->addReference(self::DYA_RIKKU, $dyaRikku);
        $this->addReference(self::KAVALLIERE, $kavalliere);
        $this->addReference(self::WISHBONE, $wishbone777);
        $this->addReference(self::YAYACHAN, $YayaChan);
    }
}
