<?php

namespace App\Tests\Artist;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ListTest extends WebTestCase
{
    public function testShowArtistList(): void
    {
        // Simululates the sending of an HTTP request
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

        $crawler = $client->request(Request::METHOD_GET, '/artists');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        // Check redirection
        $this->assertRouteSame('artist_list');
        $this->assertSelectorTextContains('h1', 'Artist list');
        $this->assertCount(4, $crawler->filter('.artist-infos'));
    }

    public function testNoCategoryExist(): void
    {
        // Simululates the sending of an HTTP request
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

        $crawler = $client->request(Request::METHOD_GET, '/artists');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        // Check redirection
        $this->assertRouteSame('artist_list');
        $this->assertSelectorTextContains('div', 'Seems there is nothing here, it\'s emptier than an interstellar void ..');
        $this->assertCount(0, $crawler->filter('.artist-infos'));
    }
}
