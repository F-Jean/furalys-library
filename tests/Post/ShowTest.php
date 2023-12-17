<?php

namespace App\Tests\Post;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowTest extends WebTestCase
{
    /**
     * @test
     */
    public function post_should_be_displayed(): void
    {
        $client = static::createClient();

        $crawler = $client->request(Request::METHOD_GET, '/post/1');

        dump($crawler->html());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount(1, $crawler->filter('.post'));
        $this->assertSelectorTextContains('h1', 'Informations sur le post');
    }
}
