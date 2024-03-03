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
    public function editPost(Post $post): void;
    public function deletePost(Post $post): void;
}