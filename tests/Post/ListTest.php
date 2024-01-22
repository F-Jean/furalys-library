<?php

namespace App\Tests\Post;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ListTest extends WebTestCase
{
    public function testShowPostsWhileLoggedIn(): void
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

        $crawler = $client->followRedirect();
        
        // Check if redirected to the posts page, app_posts, like it should
        $this->assertRouteSame('app_posts');
        $this->assertCount(16, $crawler->filter('.post-media'));
    }
}
