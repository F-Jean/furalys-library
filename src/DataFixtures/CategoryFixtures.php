<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\User;
use App\DataFixtures\UserFixtures;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
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
        $categoriesConfig = [
            self::ILLUSTRATION => [
                'title' => 'Illustration',
                'description' => 'Content about illustration.',
            ],
            self::LIVE2D => [
                'title' => 'Live2d',
                'description' => 'Content about Live2d.',
            ],
            self::ANIMATION => [
                'title' => 'Animation',
                'description' => 'Content about animation.',
            ],
        ];

        foreach ($categoriesConfig as $reference => $config) {
            $userqdoe = $this->getReference(UserFixtures::USER_QDOE, User::class);
            $category = new Category;
            $category->setTitle($config['title'])
                ->setSlug($this->slugger->slug($category->getTitle())->lower()->toString())
                ->setDescription($config['description'])
                ->setUser($userqdoe);

            $manager->persist($category);
            $this->addReference($reference, $category);
        }

        for ($i = 1; $i <= 20; $i++) {
            $userqdoe = $this->getReference(UserFixtures::USER_QDOE, User::class);
            $category = new Category();
            $category->setTitle("Category n° $i")
            ->setSlug($this->slugger->slug($category->getTitle())->lower()->toString())
            ->setDescription("Lorem ipsum")
            ->setUser($userqdoe);

            $manager->persist($category);
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
