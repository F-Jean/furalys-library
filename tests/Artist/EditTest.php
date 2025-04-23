<?php

namespace App\Tests\Artist;

use App\Entity\Artist;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EditTest extends WebTestCase
{
    public function testArtistShouldBeEditedAndRedirectToArtistList(): void
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
        /** @var Artist $originalArtist */
        $originalArtist = $entityManager->getRepository(Artist::class)->findOneById(1);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("artist_edit", ["id" => $originalArtist->getId(1)])
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
            'artist[name]' => 'new edited artist',
            'artist[description]' => 'new edited description',
            'artist[avatarFile]' => $uploadedFile,
            'artist[twitch]' => 'twitch.tv/newArtist',
            'artist[twitter]' => 'twitter.com/newArtist',
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Catch modified Artist
        $editedArtist = $entityManager->getRepository(Artist::class)->findOneById(1);
        // Compare the change of state
        $this->assertNotSame($originalArtist, $editedArtist);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('html', 'Yeah ! The artist has been modified successfully !');
    }

    public function testArtistEditShouldNotBeAvailableToOtherUsersAndRaiseErrorMessage(): void
    {
        $client = static::createClient();

        $crawler = $client->request(Request::METHOD_GET, '/login');
        // Test if it lands on the page
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "adoe@sf.com",
            "_password" => "password"
        ]);
        $client->submit($form);
        // Test if it's a FOUND (redirection code 302)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Should not be able to have access to artist 1
        $client->request(Request::METHOD_GET, '/artist/1/edit');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertSelectorTextContains('html', 'Access Denied.');
    }

    public function testArtistShouldNotBeEditedDueToBlankNameAndRaiseFormError(): void
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
            $urlGenerator->generate("artist_edit", ["id" => $artist->getId()])
        );

        $form = $crawler->filter('form[name=artist]')->form([
            'artist[name]' => '',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'Please enter a name.');
    }

    public function testArtistShouldBeEditedEvenWithBlankNullableFieldsAndRedirectToArtistList(): void
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
            $urlGenerator->generate("artist_edit", ["id" => $artist->getId()])
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
            'artist[name]' => 'new edited artist',
            'artist[description]' => '',
            'artist[avatarFile]' => $uploadedFile,
            'artist[twitch]' => '',
            'artist[twitter]' => '',
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('html', 'Yeah ! The artist has been modified successfully !');
    }
}