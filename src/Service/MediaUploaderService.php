<?php

namespace App\Service;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * MediaUploaderService class
 */
final class MediaUploaderService
{
    private string $mediaDirectory;

    /**
     * MediaUploaderService constructor
     */
    public function __construct(string $projectDir)
    {
        // Define the absolute path to the folder where the media will be stored
        $this->mediaDirectory = $projectDir . '/public/build/medias/post';
    }

    /**
     * @param Post $post
     * @return void
     */
    public function processPostMedia(Post $post): void // Processes all media files (images and videos) associated with the post.
    {
        // Processes image files
        foreach ($post->getImages() as $image) {
            $this->uploadFile($image);
        }

        // Processes video and URLs' files
        foreach ($post->getVideos() as $video) {
            $this->uploadFile($video);
            $this->handleYoutubeUrl($video);
        }
    }

    /**
     * @param object $media
     * @return void
     */
    private function uploadFile(object $media): void // Manages the physical upload of a file if supplied.
    {
        /** @var UploadedFile|null $uploadedFile */
        $uploadedFile = $media->getFile();

        if ($uploadedFile === null) {
            return;
        }

        // File name without extension
        $originalName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        // Guess the extension or replace it with .bin if unknown
        $extension = $uploadedFile->guessExtension() ?? 'bin';
        // Generates a unique file name
        $filename = uniqid($originalName . '_') . '.' . $extension;

        // Moves the file to the public directory
        $uploadedFile->move($this->mediaDirectory, $filename);

        // Updates the path in the entity
        $media->setPath($filename);
    }

    /**
     * @param object $video
     * @return void
     */
    private function handleYoutubeUrl(object $video): void // Analyses a YouTube URL and converts it into an embed version.
    {
        if ($video->getUrl() === null) {
            return;
        }

        // Basic URL for embedded YouTube videos
        $embedUrlBase = "https://www.youtube.com/embed/";
        // Keeps only the part before any ‘&’
        $urlVideo = strtok($video->getUrl(), '&');

        // Define regular expressions for the different variations of the YouTube URL
        $youtubeRegexes = [
            '#https?://(?:www\.)?youtube\.com/watch\?v=([\w-]+)#', // Classical watch https://www.youtube.com/watch?v=ID
            '#https?://(?:www\.)?youtube\.com/embed/([\w-]+)#', // Embed https://www.youtube.com/embed/ID
            '#https?://(?:www\.)?youtube\.com/v/([\w-]+)#', // /v/ format https://www.youtube.com/v/ID
            '#https?://(?:www\.)?youtube\.com/shorts/([\w-]+)#', // Shorts https://www.youtube.com/shorts/ID
            '#https?://youtu\.be/([\w-]+)#' // Short URL https://youtu.be/ID
        ];

        // Check each regular expression to find a match among expressions
        foreach ($youtubeRegexes as $regex) {
            if (preg_match($regex, $urlVideo, $matches)) {
                // Builds the embed URL and defines it in the entity
                $video->setUrl($embedUrlBase . $matches[1]);
                break; // Get out of the loop as soon as a match is found
            }
        }
    }
}
