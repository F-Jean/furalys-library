<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

/**
 * UserData class
 */
class UserData implements UserDataInterface
{
    /**
     * UserData constructor
     *
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        private EntityManagerInterface $manager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    /**
     * @param User $user
     * @return void
     */
    public function createUser(User $user): void
    {
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            $user->getPlainPassword()
        ));

        $this->manager->persist($user);
        $this->manager->flush();
    }
}