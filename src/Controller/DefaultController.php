<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;

class DefaultController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepository
    )
    {
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'posts' => $this->postRepository->findAll()
        ]);
    }
}
