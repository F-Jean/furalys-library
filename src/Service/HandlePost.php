<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;

/**
 * HandlePost class
 */
final class HandlePost implements HandlePostInterface
{
    /**
     * HandlePost constructor
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        private EntityManagerInterface $manager,
    ) {
    }

    /**
     * @param Post $post
     * @return void
     */
    public function createPost(Post $post): void
    {
        // Manages the addition of IMAGES
        foreach ($post->getImages() as $image)
        {
            $uploadedFile = $image->getFile();
            $destination = __DIR__.'/../../public/build/medias/post';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

            $uploadedFile->move(
                $destination,
                $originalFilename
            );

            $image->setPath($originalFilename);
        }

        // Manages the addition of VIDEOS
        foreach ($post->getVideos() as $video)
        {
            $uploadedFile = $video->getFile();
            $destination = __DIR__.'/../../public/build/medias/post';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

            $uploadedFile->move(
                $destination,
                $originalFilename
            );

            $video->setUrl($originalFilename);
        }

        // Manages IMAGES DATETIMEPICKERS
        // Retrieve images associated with the post
        $images = $post->getImages();

        // Browse each image to check the date
        foreach ($images as $image) {
            // Check if the date is empty
            if (empty($image->getReleasedThe())) {
                // If the date is empty, set it to null
                $image->setReleasedThe(null);
            }
        }

        // Manages VIDEOS DATETIMEPICKERS
        // Retrieve videos associated with the post
        $videos = $post->getVideos();

        // Browse each video to check the date
        foreach ($videos as $video) {
            // Check if the date is empty
            if (empty($video->getReleasedThe())) {
                // If the date is empty, set it to null
                $video->setReleasedThe(null);
            }
        }

        $this->manager->persist($post);
        $this->manager->flush();
    }
}