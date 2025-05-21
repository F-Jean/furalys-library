<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use App\Entity\Image;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * HandlePost class
 */
final class HandlePost implements HandlePostInterface
{
    /**
     * HandlePost constructor
     * 
     * @param EntityManagerInterface $manager
     * @param MediaUploaderService $mediaUploader
     */
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly MediaUploaderService $mediaUploader
    ) {}

    /**
     * @param Post $post
     * @return void
     */
    public function createPost(Post $post): void // Creates a new post with the processed files and URLs.
    {
        $this->mediaUploader->processPostMedia($post);

        // Registers the entity in the database
        $this->manager->persist($post);
        $this->manager->flush();
    }

    /**
     * @param Post $post
     * @return void
     */
    public function editPost(Post $post): void // Edit an existing post (same logic as for creation).
    {
        $this->createPost($post); // Same logic, no need for duplication
    }

    /**
     * @param Post $post
     * @return void
     */
    public function deletePost(Post $post): void // Deletes an existing post from the database.
    {
        $this->manager->remove($post);
        $this->manager->flush();
    }

    public function setThumbnailImage(Post $post, int $imageId): void
    {
        $imageToSet = null;

        foreach ($post->getImages() as $image) {
            if ($image->getId() === $imageId) {
                $imageToSet = $image;
            }
            $image->setIsThumbnail(false); // Reset all
        }

        if (!$imageToSet) {
            throw new NotFoundHttpException('Image not found.');
        }

        $imageToSet->setIsThumbnail(true);
        $this->manager->flush();
    }

    public function setThumbnailVideo(Post $post, int $videoId): void
    {
        $videoToSet = null;

        foreach ($post->getVideos() as $video) {
            if ($video->getId() === $videoId) {
                $videoToSet = $video;
            }
            $video->setIsThumbnail(false); // Reset all
        }

        if (!$videoToSet) {
            throw new NotFoundHttpException('Video not found.');
        }

        $videoToSet->setIsThumbnail(true);
        $this->manager->flush();
    }
}