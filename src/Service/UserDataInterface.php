<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

/**
 * UserDataInterface interface
 */
interface UserDataInterface
{
    /**
     * UserDataInterface constructor
     *
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $passwordHasher
    );
    public function createUser(User $user): void;
}