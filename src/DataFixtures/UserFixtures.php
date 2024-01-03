<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_QDOE = "qdoe";

    /**
     * UserFixtures constructor
     *
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    /**
     * @param ObjectManager $manager
     * @return array<string, User>
     */
    private function createUsers(ObjectManager $manager): array
    {
        // Create some users
        $users =
            [
                // Give ROLE_ADMIN
                'furalys' => (new User())->setUsername('furalys')->setEmail('furalys@sf.com')->setRole('ROLE_ADMIN'),
                // Give ROLE_USER
                'johndoe' => (new User())->setUsername('johndoe')->setEmail('johndoe@sf.com'),
                'adoe' => (new User())->setUsername('adoe')->setEmail('adoe@sf.com'),
                'bdoe' => (new User())->setUsername('bdoe')->setEmail('bdoe@sf.com'),
                'cdoe' => (new User())->setUsername('cdoe')->setEmail('cdoe@sf.com'),
                'ddoe' => (new User())->setUsername('ddoe')->setEmail('ddoe@sf.com'),
            ];

        foreach ($users as $user) {
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
        }
        return $users;
    }

    public function load(ObjectManager $manager): void
    {
        $userQdoe= new User();
        $userQdoe->setUsername('qdoe')
            ->setEmail('qdoe@sf.com')
            ->setRole('ROLE_USER')
            ->setPassword($this->passwordHasher->hashPassword($userQdoe, 'password'));
        $manager->persist($userQdoe);

        $this->createUsers($manager);
        $manager->flush();

        $this->addReference(self::USER_QDOE, $userQdoe);
    }
}
