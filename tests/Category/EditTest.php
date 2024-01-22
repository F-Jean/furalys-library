<?php

namespace App\Tests\Category;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EditTest extends WebTestCase
{
    public function testCategoryShouldBeEditedAndRedirectToCategoryList(): void
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

        // Catch original category
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var Category $originalCategory */
        $originalCategory = $entityManager->getRepository(Category::class)->findOneById(1);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("category_edit", ["id" => $originalCategory->getId(1)])
        );

        $form = $crawler->filter('form[name=category]')->form([
            'category[title]' => 'new edited category',
            'category[description]' => 'new edited description',
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Catch modified category
        $editedCategory = $entityManager->getRepository(Category::class)->findOneById(1);
        // Compare the change of state
        $this->assertNotSame($originalCategory, $editedCategory);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('html', 'Yeah ! The category has been modified successfully !');
    }

    public function testCategoryEditShouldNotBeAvailableToOtherUsersAndRaiseErrorMessage(): void
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

        // Should not be able to have access to category 1
        $client->request(Request::METHOD_GET, '/category/1/edit');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertSelectorTextContains('html', 'Access Denied.');
    }

    public function testCategoryShouldNotBeEditedDueToBlankTitleAndRaiseFormError(): void
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

        // Catch original category
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var Category $originalCategory */
        $category = $entityManager->getRepository(Category::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("category_edit", ["id" => $category->getId()])
        );

        $form = $crawler->filter('form[name=category]')->form([
            'category[title]' => '',
            'category[description]' => 'new edited description',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'Please enter a title.');
    }

    public function testCategoryShouldNotBeEditedDueToBlankDescriptionAndRaiseFormError(): void
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

        // Catch original category
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var Category $originalCategory */
        $category = $entityManager->getRepository(Category::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("category_edit", ["id" => $category->getId()])
        );

        $form = $crawler->filter('form[name=category]')->form([
            'category[title]' => 'new edited category',
            'category[description]' => '',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'Please enter a description.');
    }
}