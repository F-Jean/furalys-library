<?php

namespace App\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomepageTest extends WebTestCase
{
    public function testHomepageDisplay(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');
    
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'CONTENT COMING SOON');
    }
}
