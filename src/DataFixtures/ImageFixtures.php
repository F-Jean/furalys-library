<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Image;
use App\Entity\User;
use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ImageFixtures extends Fixture implements DependentFixtureInterface
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
            $userqdoe = $this->getReference(UserFixtures::USER_QDOE, User::class);
            $image = new Image();
            $image->setPath($config['path'])
            ->setReleasedThe(new \DateTimeImmutable())
            ->setUser($userqdoe);

            $manager->persist($image);
            $this->addReference($reference, $image);
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
