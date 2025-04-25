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
        private readonly EntityManagerInterface $manager,
    ) {
    }

    /**
     * @param Post $post
     * @return void
     */
    public function createPost(Post $post): void
    {
        // MANAGES THE ADDITION OF IMAGES
        foreach ($post->getImages() as $image)
        {
            // If Image.file is filled
            if ($image->getFile() !== null) {
                $uploadedFile = $image->getFile();
                $destination = __DIR__.'/../../public/build/medias/post';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

                $uploadedFile->move(
                    $destination,
                    $originalFilename
                );

                $image->setPath($originalFilename);
            }
        }

        // MANAGES THE ADDITION OF VIDEOS
        foreach ($post->getVideos() as $video)
        {
            // If video.file is filled
            if ($video->getFile() !== null) {
                $uploadedFile = $video->getFile();
                $destination = __DIR__.'/../../public/build/medias/post';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

                $uploadedFile->move(
                    $destination,
                    $originalFilename
                );

                $video->setPath($originalFilename);
            } elseif ($video->getUrl() !== null) {
                // Basic URL for embedded YouTube videos
                $embedUrlBase = "https://www.youtube.com/embed/";
                // Get the video URL
                $urlVideo = $video->getUrl();
            
                // Define regular expressions for different variations of the YouTube URL
                $youtubeRegexes = [
                    '#https?://(?:www\.)?youtube\.com/watch\?v=([\w-]+)#',
                    '#(?:www\.)?youtube\.com/watch\?v=([\w-]+)#',
                    '#youtube\.com/watch\?v=([\w-]+)#'
                ];
            
                // Check each regular expression to find the YouTube video ID
                $ytId = null;
                foreach ($youtubeRegexes as $regex) {
                    if (preg_match($regex, $urlVideo, $matches)) {
                        $ytId = $matches[1];
                        break; // Get out of the loop as soon as a match is found
                    }
                }
            
                // If a valid video identifier has been found
                if ($ytId !== null) {
                    // Build the integration URL with the video identifier
                    $embedUrl = $embedUrlBase . $ytId;
                    // Define the video URL as the embed URL
                    $video->setUrl($embedUrl);
                }
            }
        }

        // MANAGES IMAGES DATETIMEPICKERS
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

        // MANAGES VIDEOS DATETIMEPICKERS
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

    /**
     * @param Post $post
     * @return void
     */
    public function editPost(Post $post): void
    {
        // MANAGES THE ADDITION OF IMAGES
        foreach ($post->getImages() as $image)
        {
            // If Image.file is filled
            if ($image->getFile() !== null) {
                $uploadedFile = $image->getFile();
                $destination = __DIR__.'/../../public/build/medias/post';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

                $uploadedFile->move(
                    $destination,
                    $originalFilename
                );

                $image->setPath($originalFilename);
            }
        }

        // MANAGES THE ADDITION OF VIDEOS
        foreach ($post->getVideos() as $video)
        {
            // If video.file is filled
            if ($video->getFile() !== null) {
                $uploadedFile = $video->getFile();
                $destination = __DIR__.'/../../public/build/medias/post';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

                $uploadedFile->move(
                    $destination,
                    $originalFilename
                );

                $video->setPath($originalFilename);
            } elseif ($video->getUrl() !== null) {
                // Basic URL for embedded YouTube videos
                $embedUrlBase = "https://www.youtube.com/embed/";
                // Get the video URL
                $urlVideo = $video->getUrl();
            
                // Define regular expressions for different variations of the YouTube URL
                $youtubeRegexes = [
                    '#https?://(?:www\.)?youtube\.com/watch\?v=([\w-]+)#',
                    '#(?:www\.)?youtube\.com/watch\?v=([\w-]+)#',
                    '#youtube\.com/watch\?v=([\w-]+)#'
                ];
            
                // Check each regular expression to find the YouTube video ID
                $ytId = null;
                foreach ($youtubeRegexes as $regex) {
                    if (preg_match($regex, $urlVideo, $matches)) {
                        $ytId = $matches[1];
                        break; // Get out of the loop as soon as a match is found
                    }
                }
            
                // If a valid video identifier has been found
                if ($ytId !== null) {
                    // Build the integration URL with the video identifier
                    $embedUrl = $embedUrlBase . $ytId;
                    // Define the video URL as the embed URL
                    $video->setUrl($embedUrl);
                }
            }
        }

        // MANAGES IMAGES DATETIMEPICKERS
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

        // MANAGES VIDEOS DATETIMEPICKERS
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

    /**
     * @param Post $post
     * @return void
     */
    public function deletePost(Post $post): void
    {
        $this->manager->remove($post);
        $this->manager->flush();
    }
}