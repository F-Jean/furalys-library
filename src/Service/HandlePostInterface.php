<?php

namespace App\Service;

use App\Entity\Post;

/**
 * HandlePostInterface interface
 */
interface HandlePostInterface
{
    public function createPost(Post $post): void;
    public function editPost(Post $post): void;
    public function deletePost(Post $post): void;
    public function setThumbnailImage(Post $post, int $imageId): void;
    public function setThumbnailVideo(Post $post, int $videoId): void;
}