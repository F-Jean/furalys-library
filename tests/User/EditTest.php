<?php

namespace App\Tests\User;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class EditTest extends WebTestCase
{
    public function testUserShouldBeEditedByAdminAndRedirectToUserList(): void
    {
        $client = static::createClient();

        // Login user furalys (only admin at the moment)
        $crawler = $client->request(Request::METHOD_GET, '/login');
        // Test if it lands on the page
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "furalys@sf.com",
            "_password" => "password"
        ]);
        $client->submit($form);
        // Test if it's a FOUND (redirection code 302)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Catch original user
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var User $originalUser */
        $originalUser = $entityManager->getRepository(User::class)->findOneById(4);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("user_edit", ["id" => $originalUser->getId()])
        );

        $form = $crawler->filter('form[name=user]')->form([
            'user[email]' => 'newemail@sf.com',
            'user[username]' => 'new edited username',
            'user[plainPassword][first]' => '!NewPassword1',
            'user[plainPassword][second]' => '!NewPassword1',
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Catch modified Artist
        $editedUser = $entityManager->getRepository(User::class)->findOneById(4);
        // Compare the change of state
        $this->assertNotSame($originalUser, $editedUser);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('html', 'Yeah ! User data successfully updated');
    }

    public function testUserEditShouldNotBeAvailableToNonAdminAndRaiseErrorMessage(): void
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

        // Should not be able to have access to artist 1
        $client->request(Request::METHOD_GET, '/user/1/edit');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertSelectorTextContains('html', 'Access Denied by #[IsGranted("ROLE_ADMIN")] on controller');
    }

    public function testUserShouldNotBeEditedDueToBlankUsernameAndRaiseFormError(): void
    {
        $client = static::createClient();

        // Login user qdoe (only one that have artists at the moment)
        $crawler = $client->request(Request::METHOD_GET, '/login');
        // Test if it lands on the page
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "furalys@sf.com",
            "_password" => "password"
        ]);
        $client->submit($form);
        // Test if it's a FOUND (redirection code 302)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Catch original artist
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("user_edit", ["id" => $user->getId()])
        );

        $form = $crawler->filter('form[name=user]')->form([
            'user[username]' => '',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'Please enter a username.');
    }

    public function testUserShouldNotBeEditedDueToBlankPasswordAndRaiseFormError(): void
    {
        $client = static::createClient();

        // Login user qdoe (only one that have artists at the moment)
        $crawler = $client->request(Request::METHOD_GET, '/login');
        // Test if it lands on the page
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "furalys@sf.com",
            "_password" => "password"
        ]);
        $client->submit($form);
        // Test if it's a FOUND (redirection code 302)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Catch original artist
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("user_edit", ["id" => $user->getId()])
        );

        $form = $crawler->filter('form[name=user]')->form([
            'user[email]' => 'furalys@sf.com',
            'user[username]' => 'new edited username',
            'user[plainPassword][first]' => '',
            'user[plainPassword][second]' => '',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'Please enter a password.');
    }

    public function testUserShouldNotBeEditedDueToBlankEmailAndRaiseFormError(): void
    {
        $client = static::createClient();

        // Login user qdoe (only one that have artists at the moment)
        $crawler = $client->request(Request::METHOD_GET, '/login');
        // Test if it lands on the page
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "furalys@sf.com",
            "_password" => "password"
        ]);
        $client->submit($form);
        // Test if it's a FOUND (redirection code 302)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Catch original artist
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("user_edit", ["id" => $user->getId()])
        );

        $form = $crawler->filter('form[name=user]')->form([
            'user[email]' => '',
            'user[username]' => 'new edited username',
            'user[plainPassword][first]' => '!NewPassword1',
            'user[plainPassword][second]' => '!NewPassword1',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'Please enter an email.');
    }

    public function testUserShouldNotBeEditedDueToDuplicateEmailAndRaiseFormError(): void
    {
        $client = static::createClient();

        // Login user qdoe (only one that have artists at the moment)
        $crawler = $client->request(Request::METHOD_GET, '/login');
        // Test if it lands on the page
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "furalys@sf.com",
            "_password" => "password"
        ]);
        $client->submit($form);
        // Test if it's a FOUND (redirection code 302)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Catch original artist
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("user_edit", ["id" => $user->getId()])
        );

        $form = $crawler->filter('form[name=user]')->form([
            'user[email]' => 'adoe@sf.com',
            'user[username]' => 'new edited username',
            'user[plainPassword][first]' => '!NewPassword1',
            'user[plainPassword][second]' => '!NewPassword1',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'Invalid credentials.');
    }

    public function testUserShouldNotBeEditedDueToWrongPasswordFormatAndRaiseFormError(): void
    {
        $client = static::createClient();

        // Login user qdoe (only one that have artists at the moment)
        $crawler = $client->request(Request::METHOD_GET, '/login');
        // Test if it lands on the page
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "furalys@sf.com",
            "_password" => "password"
        ]);
        $client->submit($form);
        // Test if it's a FOUND (redirection code 302)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Catch original artist
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("user_edit", ["id" => $user->getId()])
        );

        $form = $crawler->filter('form[name=user]')->form([
            'user[email]' => 'furalys@sf.com',
            'user[username]' => 'new edited username',
            'user[plainPassword][first]' => 'password',
            'user[plainPassword][second]' => 'password',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'Incorrect password format.');
    }

    public function testUserShouldNotBeEditedDueToNotSameFieldsPasswordAndRaiseFormError(): void
    {
        $client = static::createClient();

        // Login user qdoe (only one that have artists at the moment)
        $crawler = $client->request(Request::METHOD_GET, '/login');
        // Test if it lands on the page
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "furalys@sf.com",
            "_password" => "password"
        ]);
        $client->submit($form);
        // Test if it's a FOUND (redirection code 302)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Catch original artist
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy([]);

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("user_edit", ["id" => $user->getId()])
        );

        $form = $crawler->filter('form[name=user]')->form([
            'user[email]' => 'furalys@sf.com',
            'user[username]' => 'new edited username',
            'user[plainPassword][first]' => '!NewPassword1',
            'user[plainPassword][second]' => '!NewPassword2',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('html', 'Passwords don\'t match');
    }
}