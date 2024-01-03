<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Artist;
use App\DataFixtures\UserFixtures;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArtistFixtures extends Fixture implements DependentFixtureInterface
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
        $artistsConfig = [
            self::DYA_RIKKU => [
                'name' => 'DyaRikku',
                'description' => '2D Artist/Illustrator, Live2D Rigger, Vtuber. Where is my pink dog',
                'avatar' => '/avatar/DyaRikku_avatar.png',
                'twitch' => 'twitch.tv/dyarikku',
                'twitter' => 'twitter.com/dyarikku',
            ],
            self::WISHBONE => [
                'name' => 'wishbone777',
                'description' => 'You can call me ゆう。or wishbone',
                'avatar' => '/avatar/wishbone777_avatar.png',
                'twitter' => 'twitter.com/wishbone777',
            ],
            self::KAVALLIERE => [
                'name' => 'Kavalliere',
                'description' => '𝘝𝘛𝘶𝘣𝘦𝘳 𝘔𝘢𝘮𝘢 𝘢𝘯𝘥 𝘚𝘵𝘳𝘦𝘢𝘮𝘦𝘳 // The Lady of Fukurou Sanctuary',
                'avatar' => '/avatar/kavalliere_avatar.png',
                'twitch' => 'twitch.tv/kavalliere',
                'twitter' => 'twitter.com/Kavalliere_',
            ],
            self::YAYACHAN => [
                'name' => 'YayaChan',
                'description' => 'イラストレーター',
                'avatar' => '/avatar/YayaChan_avatar.png',
                'twitter' => 'twitter.com/YayaChanArtist',
            ],
        ];

        foreach ($artistsConfig as $reference => $config) {
            $userQdoe = $this->getReference(UserFixtures::USER_QDOE);
            $artist = new Artist;
            $artist->setName($config['name'])
                ->setSlug($this->slugger->slug($artist->getName())->lower()->toString())
                ->setDescription($config['description'])
                ->setAvatar($config['avatar'])
                ->setUser($userQdoe);

            // if a twitch field is detected then add it
            if (isset($config['twitch'])) {
                $artist->setTwitch($config['twitch']);
            }
            // if a twitter field is detected then add it
            if (isset($config['twitter'])) {
                $artist->setTwitter($config['twitter']);
            }

            $manager->persist($artist);
            $this->addReference($reference, $artist);
        }

        $manager->flush();
    }

    // return an array of the fixture classes that must be loaded before this one
    /**
     * @return array<int, string>
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
