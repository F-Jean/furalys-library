<?php

namespace App\Tests\Post;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class CreateTest extends WebTestCase
{
    public function testNewCategoryShouldBeDisplayedAndRedirectToCategoryList(): void
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
            $urlGenerator->generate("category_create")
        );

        $form = $crawler->filter('form[name=category]')->form([
            'category[title]' => 'new category',
            'category[description]' => 'new description',
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains('html', 'The category has been created successfully.');
    }

    public function testNewCategoryShouldNotBeRegisteredDueToBlankTitleAndRaiseFormError(): void
    {
        $client = static::createClient();

        // Login user qdoe (only one that have categories at the moment)
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

        // Catch category
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var Category $category */
        $category = $entityManager->getRepository(Category::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("category_create", ["id" => $category->getId()])
        );

        $form = $crawler->filter('form[name=category]')->form([
            'category[title]' => '',
            'category[description]' => 'new description',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'Please enter a title.');
    }

    public function testNewCategoryShouldNotBeEditedDueToBlankDescriptionAndRaiseFormError(): void
    {
        $client = static::createClient();

        // Login user qdoe (only one that have categories at the moment)
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

        // Catch category
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var Category $category */
        $category = $entityManager->getRepository(Category::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("category_create", ["id" => $category->getId()])
        );

        $form = $crawler->filter('form[name=category]')->form([
            'category[title]' => 'new category',
            'category[description]' => '',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'Please enter a description.');
    }

    public function testNewCategoryShouldNotBeRegisteredDueToExistingTitleAndRaiseFormError(): void
    {
        $client = static::createClient();

        // Login user qdoe (only one that have categories at the moment)
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

        // Catch category
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var Category $category */
        $category = $entityManager->getRepository(Category::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("category_create", ["id" => $category->getId()])
        );

        $form = $crawler->filter('form[name=category]')->form([
            'category[title]' => 'Illustration',
            'category[description]' => 'Lorem ipsum',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'This category already exists.');
    }
}