<?php

namespace App\Tests\Artist;

use App\Entity\Artist;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteTest extends WebTestCase
{
    public function testArtistShouldBeDeletedByAuthorAndRedirectToArtistList(): void
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

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var Artist $artist */
        $artist = $entityManager->getRepository(Artist::class)->find(1);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("artist_delete", ["id" => $artist->getId()])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('html', 'The artist has been deleted successfully !');
        $this->assertResponseIsSuccessful();

        $artist = $entityManager->getRepository(Artist::class)->find(1);
        $this->assertNull($artist);
    }

    public function testArtistShouldNotBeDeletedByOtherUserAndRaiseMessageError(): void
    {
        $client = static::createClient();
        // Recover the crawler then request the access to the login page
        $crawler = $client->request(Request::METHOD_GET, '/login');
        // Test if it lands on the page
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Crawler allows to recover the content of a page
        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "adoe@sf.com",
            "_password" => "password"
        ]);
        $client->submit($form);
        // Test if it's a FOUND (redirection code 302)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $client->request(Request::METHOD_GET, '/artist/1/delete');

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertSelectorTextContains('html', 'Access Denied.');
    }
}