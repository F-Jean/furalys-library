<?php

namespace App\Service;

use App\Entity\Artist;

/**
 * HandleArtistInterface interface
 */
interface HandleArtistInterface
{
    public function createArtist(Artist $artist): void;
    public function editArtist(Artist $artist): void;
    public function deleteArtist(Artist $artist): void;
}