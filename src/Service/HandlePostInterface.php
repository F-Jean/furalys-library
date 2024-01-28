<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;

/**
 * HandlePostInterface interface
 */
interface HandlePostInterface
{
    public function __construct(
        EntityManagerInterface $manager
    );
    public function createPost(Post $post): void;
}