<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Image;

class ImageFixtures extends Fixture
{
    public const POST1 = "post1";
    public const POST2 = "post2";

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $post1 = new Image();
        $post1->setPath('tyApr.jpg');
        $manager->persist($post1);

        $post2 = new Image();
        $post2->setPath('tyMar.jpg');
        $manager->persist($post2);

        $manager->flush();

        $this->addReference(self::POST1, $post1);
        $this->addReference(self::POST2, $post2);
    }
}
