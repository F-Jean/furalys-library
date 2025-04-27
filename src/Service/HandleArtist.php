<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Artist;

/**
 * HandleArtist class
 */
final class HandleArtist implements HandleArtistInterface
{
    /**
     * HandleArtist constructor
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly SluggerInterface $slugger
    ) {
    }

    /**
     * @param Artist $artist
     * @return void
     */
    public function createArtist(Artist $artist): void
    {
        $uploadedFile = $artist->getAvatarFile();
        $destination = __DIR__.'/../../public/build/medias/avatar';
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile->move(
            $destination,
            $newFilename
        );

        $artist->setAvatar($newFilename);
        $this->manager->persist($artist);
        $this->manager->flush();
    }

    /**
     * @return void
     */
    public function editArtist(Artist $artist): void
    {
        $uploadedFile = $artist->getAvatarFile();
        $destination = __DIR__.'/../../public/build/medias/avatar';
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile->move(
            $destination,
            $newFilename
        );

        $artist->setAvatar($newFilename);
        $this->manager->persist($artist);
        $this->manager->flush();
    }

    /**
     * @param Artist $artist
     * @return void
     */
    public function deleteArtist(Artist $artist): void
    {
        // Delete avatar when artist is deleted
        $presentAvatar = $artist->getAvatar();
        if($presentAvatar)
        {
            $presentAvatarName = __DIR__.'/../../public/build/medias/avatar'.'/'.$artist->getAvatar();
            // Check if avatar exist
            if(file_exists($presentAvatarName))
            {
                unlink($presentAvatarName);
            }
        }

        $this->manager->remove($artist);
        $this->manager->flush();
    }
}