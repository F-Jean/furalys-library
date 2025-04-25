<?php

namespace App\Service;

use App\Entity\User;

/**
 * UserDataInterface interface
 */
interface UserDataInterface
{
    public function editUser(User $user): void;
    public function deleteUser(User $user): void;
}