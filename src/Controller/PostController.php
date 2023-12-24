<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;

class PostController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepository
    )
    {
    }

    #[Route('/posts', name: 'app_posts')]
    public function index(): Response
    {
        return $this->render('post/list.html.twig', [
            'posts' => $this->postRepository->findAll()
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
