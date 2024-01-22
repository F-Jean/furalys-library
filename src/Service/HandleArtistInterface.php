<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Artist;

/**
 * HandleArtistInterface interface
 */
interface HandleArtistInterface
{
    public function __construct(
        EntityManagerInterface $manager, 
        SluggerInterface $slugger
    );
    public function createArtist(Artist $artist): void;
    public function editArtist(Artist $artist): void;
    public function deleteArtist(Artist $artist): void;
}