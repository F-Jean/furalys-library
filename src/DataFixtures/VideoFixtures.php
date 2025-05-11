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

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $userqdoe = $this->getReference(UserFixtures::USER_QDOE, User::class);
        $video1 = new Video();
        $video1->setPath('@Kavalliere__21_11_23_1a.mp4')
        ->setReleasedThe(new \DateTimeImmutable())
        ->setUser($userqdoe);
        $manager->persist($video1);

        $manager->flush();

        $this->addReference(self::VIDEO1, $video1);
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
