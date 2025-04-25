<?php

namespace App\Tests\Artist;

use App\Entity\Artist;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class CreateTest extends WebTestCase
{
    public function testNewArtistShouldBeDisplayedAndRedirectToArtistList(): void
    {
        $client = static::createClient();
        // Recover the crawler then request the access to the login page
        $crawler = $client->request(Request::METHOD_GET, '/login');
        // Test if it lands on the page
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Crawler allows to recover the content of a page
        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "qdoe@sf.com",
            "_password" => "password"
        ]);
        $client->submit($form);
        // Test if it's a FOUND (redirection code 302)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("artist_create")
        );

        // Absolute path from the file
        $imagePath = __DIR__.'/../../public/build/medias/avatar/DyaRikku_avatar.png';

        // Create object UploadedFile from file path
        $uploadedFile = new UploadedFile(
            $imagePath,
            'DyaRikku_avatar.png',
            'image/png',
            null,
            true
        );

        $form = $crawler->filter('form[name=artist]')->form([
            'artist[name]' => 'new artist',
            'artist[description]' => 'new description',
            'artist[avatarFile]' => $uploadedFile,
            'artist[twitch]' => 'twitch.tv/newArtist',
            'artist[twitter]' => 'twitter.com/newArtist',
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains('html', 'The artist has been created successfully.');
    }

    public function testNewArtistShouldNotBeRegisteredDueToBlankNameAndRaiseFormError(): void
    {
        $client = static::createClient();

        // Login user qdoe (only one that have artists at the moment)
        $crawler = $client->request(Request::METHOD_GET, '/login');
        // Test if it lands on the page
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "qdoe@sf.com",
            "_password" => "password"
        ]);
        $client->submit($form);
        // Test if it's a FOUND (redirection code 302)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Catch original artist
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var Artist $artist */
        $artist = $entityManager->getRepository(Artist::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("artist_create", ["id" => $artist->getId()])
        );

        $form = $crawler->filter('form[name=artist]')->form([
            'artist[name]' => '',
            'artist[description]' => 'new description',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'Please enter a name.');
    }

    public function testNewArtistShouldBeEditedEvenWithBlankNullableFieldsAndRedirectToArtistList(): void
    {
        $client = static::createClient();

        // Login user qdoe (only one that have artists at the moment)
        $crawler = $client->request(Request::METHOD_GET, '/login');
        // Test if it lands on the page
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "qdoe@sf.com",
            "_password" => "password"
        ]);
        $client->submit($form);
        // Test if it's a FOUND (redirection code 302)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Catch original artist
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var Artist $artist */
        $artist = $entityManager->getRepository(Artist::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("artist_create", ["id" => $artist->getId()])
        );

        // Absolute path from the file
        $imagePath = __DIR__.'/../../public/build/medias/avatar/DyaRikku_avatar.png';

        // Create object UploadedFile from file path
        $uploadedFile = new UploadedFile(
            $imagePath,
            'DyaRikku_avatar.png',
            'image/png',
            null,
            true
        );

        $form = $crawler->filter('form[name=artist]')->form([
            'artist[name]' => 'new name',
            'artist[description]' => '',
            'artist[avatarFile]' => $uploadedFile,
            'artist[twitch]' => '',
            'artist[twitter]' => '',
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains('html', 'The artist has been created successfully.');
    }

    public function testNewArtistShouldNotBeEditedDueToNoAvatarAndRaiseFormError(): void
    {
        $client = static::createClient();

        // Login user qdoe (only one that have artists at the moment)
        $crawler = $client->request(Request::METHOD_GET, '/login');
        // Test if it lands on the page
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "qdoe@sf.com",
            "_password" => "password"
        ]);
        $client->submit($form);
        // Test if it's a FOUND (redirection code 302)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Catch original artist
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var Artist $artist */
        $artist = $entityManager->getRepository(Artist::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("artist_create", ["id" => $artist->getId()])
        );

        $form = $crawler->filter('form[name=artist]')->form([
            'artist[name]' => 'new name',
            'artist[description]' => 'new description',
            'artist[avatarFile]' => '',
            'artist[twitch]' => 'twitch.tv/newArtist',
            'artist[twitter]' => 'twitter.com/newArtist',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'Please provide an image.');
    }

    public function testNewArtistShouldNotBeRegisteredDueToExistingNameAndRaiseFormError(): void
    {
        $client = static::createClient();

        // Login user qdoe (only one that have artists at the moment)
        $crawler = $client->request(Request::METHOD_GET, '/login');
        // Test if it lands on the page
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "qdoe@sf.com",
            "_password" => "password"
        ]);
        $client->submit($form);
        // Test if it's a FOUND (redirection code 302)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Catch original artist
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var Artist $artist */
        $artist = $entityManager->getRepository(Artist::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("artist_create", ["id" => $artist->getId()])
        );

        $form = $crawler->filter('form[name=artist]')->form([
            'artist[name]' => 'DyaRikku',
            'artist[description]' => 'Lorem ipsum',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'This artist already exists.');
    }
}