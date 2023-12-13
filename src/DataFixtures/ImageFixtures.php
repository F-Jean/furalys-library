<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Image;

class ImageFixtures extends Fixture
{
    public const IMG1 = "img1";
    public const IMG2 = "img2";
    public const IMG3 = "img3";
    public const IMG4 = "img4";
    public const IMG5 = "img5";
    public const IMG6 = "img6";
    public const IMG7 = "img7";
 
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $imagesConfig = [
            self::IMG1 => [
                'path' => 'tyApr.jpg'
            ],
            self::IMG2 => [
                'path' => 'tyMar.jpg'
            ],
            self::IMG3 => [
                'path' => '@wishbone777_01_06_23_1g.jpg'
            ],
            self::IMG4 => [
                'path' => 'ds_1.jpg'
            ],
            self::IMG5 => [
                'path' => 'ds_2.jpg'
            ],
            self::IMG6 => [
                'path' => 'ds_3.jpg'
            ],
            self::IMG7 => [
                'path' => 'ds_4.jpg'
            ],
        ];

        foreach ($imagesConfig as $reference => $config) {
            $image = new Image();
            $image->setPath($config['path']);

            $manager->persist($image);
            $this->addReference($reference, $image);
        }

        $manager->flush();
    }
}
