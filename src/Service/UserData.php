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
        private readonly EntityManagerInterface $manager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    /**
     * @param User $user
     * @return void
     */
    public function editUser(User $user): void
    {
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            $user->getPlainPassword()
        ));

        $this->manager->flush();
    }

    /**
     * @param User $user
     * @return void
     */
    public function deleteUser(User $user): void
    {
        $this->manager->remove($user);
        $this->manager->flush();
    }
}