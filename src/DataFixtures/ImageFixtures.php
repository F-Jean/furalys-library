<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Image;

class ImageFixtures extends Fixture
{
    public const POST1 = "post1";
    public const POST2 = "post2";
    public const POST3 = "post3";
    public const POST4 = "post4";
    public const POST5 = "post5";
    public const POST6 = "post6";
    public const POST7 = "post7";
 

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

        $post3 = new Image();
        $post3->setPath('@wishbone777_01_06_23_1g.jpg');
        $manager->persist($post3);

        $post4 = new Image();
        $post4->setPath('ds_1.jpg');
        $manager->persist($post4);

        $post5 = new Image();
        $post5->setPath('ds_2.jpg');
        $manager->persist($post5);

        $post6 = new Image();
        $post6->setPath('ds_3.jpg');
        $manager->persist($post6);

        $post7 = new Image();
        $post7->setPath('ds_4.jpg');
        $manager->persist($post7);

        $manager->flush();

        $this->addReference(self::POST1, $post1);
        $this->addReference(self::POST2, $post2);
        $this->addReference(self::POST3, $post3);
        $this->addReference(self::POST4, $post4);
        $this->addReference(self::POST5, $post5);
        $this->addReference(self::POST6, $post6);
        $this->addReference(self::POST7, $post7);
    }
}
