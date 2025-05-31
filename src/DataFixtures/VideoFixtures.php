<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Video;
use App\Entity\User;
use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class VideoFixtures extends Fixture implements DependentFixtureInterface
{
    public const VIDEO1 = "video1";
    public const VIDEO2 = "video2";
    public const VIDEO3 = "video3";
    public const VIDEO4 = "video4";
    public const VIDEO5 = "video5";

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $videosConfig = [
            self::VIDEO1 => [
                'path' => '@Kavalliere__21_11_23_1a.mp4'
            ],
            self::VIDEO2 => [
                'path' => 'MINTOITSYOURBIRTHDAY.mp4'
            ],
            self::VIDEO3 => [
                'path' => 'test.mp4'
            ],
            self::VIDEO4 => [
                'path' => '@hoopyon_2024_07_17_1a.mp4'
            ],
            self::VIDEO5 => [
                'path' => '@aimusu1208_2025_01_17_1a.mp4'
            ],
        ];

        foreach ($videosConfig as $reference => $config) {
            $userqdoe = $this->getReference(UserFixtures::USER_QDOE, User::class);
            $video = new Video();
            $video->setPath($config['path'])
            ->setReleasedThe(new \DateTimeImmutable())
            ->setUser($userqdoe);

            $manager->persist($video);
            $this->addReference($reference, $video);
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
