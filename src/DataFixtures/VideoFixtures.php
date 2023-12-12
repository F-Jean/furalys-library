<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Video;

class VideoFixtures extends Fixture
{
    public const VIDEO1 = "video1";

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $video1 = new Video();
        $video1->setUrl('@Kavalliere__21_11_23_1a.mp4');
        $manager->persist($video1);

        $manager->flush();

        $this->addReference(self::VIDEO1, $video1);
    }
}
