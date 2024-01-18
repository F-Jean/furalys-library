<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class PostController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepository,
        private UserRepository $userRepository
    )
    {
    }

    #[Route('/posts', name: 'app_posts')]
    public function index(): Response
    {
        //Recover the connected user
        $user = $this->getUser();

        // Check that the user is connected
        if (!$user instanceof User) {
            throw new AccessDeniedException('You have to connect first.');
        } else {
            // If user is connected, recover THEIR posts
            $userPosts = $this->postRepository->findBy(['user' => $user], ['id' => 'DESC'], 16);
        }

        return $this->render('post/list.html.twig', [
            'posts' => $userPosts ?? [],
            'user' => $user
        ]);
    }

    #[Route('/posts/load_more/{page}', name: 'post_load_more', requirements: ['page' => '\d+'])]
    public function loadMore(int $page): Response
    {
        //Recover the connected user
        $user = $this->getUser();

        return $this->render("post/_posts.html.twig", [
            'posts' => $this->postRepository->getPosts($page, 8),
            'user' => $user
        ]);
    }

    #[Route('/post/{id}', name: 'post_show')]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
