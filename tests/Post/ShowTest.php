<?php

namespace App\Tests\Post;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowTest extends WebTestCase
{
    public function testPostShouldBeDisplayed(): void
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

        $crawler = $client->request(Request::METHOD_GET, '/post/1');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount(1, $crawler->filter('.post'));
        $this->assertSelectorTextContains('h1', 'Informations about the post');
    }
}
