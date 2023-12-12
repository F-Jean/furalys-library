<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    public const ILLUSTRATION = "Illustration";
    public const LIVE2D = "Live2d";
    public const ANIMATION = "Animation";

    public function __construct(private SluggerInterface $slugger)
    {
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $illustration = new Category;
        $illustration->setTitle('Illustration')
        ->setSlug($this->slugger->slug($illustration->getTitle())->lower()->toString())
        ->setDescription('Content about illustration.');
        $manager->persist($illustration);

        $live2d = new Category;
        $live2d->setTitle('Live2d')
        ->setSlug($this->slugger->slug($live2d->getTitle())->lower()->toString())
        ->setDescription('Content about Live2d.');
        $manager->persist($live2d);

        $animation = new Category;
        $animation->setTitle('Animation')
        ->setSlug($this->slugger->slug($animation->getTitle())->lower()->toString())
        ->setDescription('Content about animation.');
        $manager->persist($animation);

        $manager->flush();

        $this->addReference(self::ILLUSTRATION, $illustration);
        $this->addReference(self::LIVE2D, $live2d);
        $this->addReference(self::ANIMATION, $animation);
    }
}
