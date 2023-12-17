<?php

namespace App\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomepageTest extends WebTestCase
{
    /**
     * @test
     */
    public function posts_should_be_displayed(): void
    {
        $client = static::createClient();

        /*
            Retrieve the router to generate a direct url
            because later the url may evolve, so we don't want to write it by hand
        */
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("homepage")
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount(5, $crawler->filter('.card'));
    }
}
