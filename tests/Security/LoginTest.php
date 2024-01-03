<?php

namespace App\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class LoginTest extends WebTestCase
{
    public function testUserShouldBeAuthenticatedAndRedirectToPostsPageWithoutPosts(): void
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

        $crawler = $client->followRedirect();
        
        // Check if redirected to the posts page, app_posts, like it should
        $this->assertRouteSame('app_posts');
        $this->assertSelectorTextContains('p', 'No posts yet. Add your first post!');
        $this->assertCount(0, $crawler->filter('.post-media'));
    }

    public function testUserShouldBeAuthenticatedAndRedirectToPostsPageWithPosts(): void
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
        $this->assertCount(5, $crawler->filter('.post-media'));
    }

    public function testUserShouldNotBeAuthenticatedDueToInvalidCredentialsAndRaiseFormError(): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "adoe@sf.com",
            "_password" => "fail"
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('html', 'Invalid credentials.');
    }

    public function testUserShouldNotBeAuthenticatedDueToBlankUsernameRaiseFormErrorAndRedirectToLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "",
            "_password" => "passworD1!"
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();
        $this->assertSelectorTextContains('html', 'Invalid credentials.');
    }

    public function testUserShouldNotBeAuthenticatedDueToBlankPasswordRaiseFormErrorAndRedirectToLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "Jean",
            "_password" => ""
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();
        $this->assertSelectorTextContains('html', 'Invalid credentials.');
    }
}
