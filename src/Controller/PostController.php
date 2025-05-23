<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Repository\VideoRepository;
use App\Service\HandlePostInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class PostController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepository,
        private VideoRepository $videoRepository,
        private HandlePostInterface $handlePost,
    )
    {
    }

    // PAGE DISPLAYING ALL THE POSTS
    #[Route('/posts', name: 'app_posts')]
    public function index(): Response
    {
        //Recover the connected user
        $user = $this->getUser();

        // Check that the user is connected
        if (!$user instanceof User) {
            throw new AccessDeniedException('You have to connect first.');
        }

        // If user is connected, recover THEIR posts and videos
        $userPosts = $this->postRepository->findBy(['user' => $user], ['id' => 'DESC'], 16);
        // Find all the user videos
        $userVideos = $this->videoRepository->findBy(['user' => $user]);

        return $this->render('post/list.html.twig', [
            'posts' => $userPosts,
            'user' => $user,
            'video' => $userVideos,
        ]);
    }

    // METHOD LOADING MORE POSTS
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

    // PAGE DISPLAYING THE FORM POST
    #[Route('/post/create', name: 'post_create')]
    public function createAction(
        Request $request,
    ): Response
    {
        //Recover the connected user
        /** @var User $user */
        $user = $this->getUser();
        $post = new Post();
        $post->setUser($user);

        // Filters the content of logged-in user
        $filteredArtists = $user->getArtists();
        $filteredCategories = $user->getCategories();

        $form = $this->createForm(
            PostType::class,
            $post, 
            [
                'validation_groups' => ['Default', 'add'],
                'filtered_Artists' => $filteredArtists,
                'filtered_Categories' => $filteredCategories,
            ]
        )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if at least one media is added
            if ($post->getImages()->isEmpty() && $post->getVideos()->isEmpty()) {
                $this->addFlash(
                    'error',
                    'Please choose at least one media (image or video).'
                );
                return $this->redirectToRoute('post_create');
            }

            // Check we don't have two fields (File & url) filled for 1 video
            foreach ($post->getVideos() as $video) {
                if ($video->getFile() && $video->getUrl()) {
                    $this->addFlash(
                        'error',
                        'Please fill only one of the "File" or "URL" fields for each video.'
                    );
                    return $this->redirectToRoute('post_create');
                }
            }

            // If user choose to enter an url
            // verify that it's a Youtube url only
            foreach ($post->getVideos() as $video) {
                $urlVideo = $video->getUrl();
                if (!empty($urlVideo) && 
                    !str_starts_with($urlVideo, "https://www.youtube.com/watch?v=") &&
                    !str_starts_with($urlVideo, "www.youtube.com/watch?v=") &&
                    !str_starts_with($urlVideo, "youtube.com/watch?v=")) {
                        $this->addFlash(
                            'error',
                            'Please choose a Youtube URL only.'
                        );
                    return $this->redirectToRoute('post_create');
                }
            }

            // If all previous validations pass, proceed with post creation
            $this->handlePost->createPost($post);
            $this->addFlash(
                'success',
                'The post has been created successfully.'
            );
            // Redirect to the newly created post
            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }
        return $this->render('post/create.html.twig', [
            'postForm' => $form->createView(),
            'filtered_Artists' => $filteredArtists,
            'filtered_Categories' => $filteredCategories,
        ]);
    }

    // PAGE DISPLAYING ONE PARTICULAR POST DETAILS
    // Keep below the create method otherwise they enter in conflicts
    #[Route('/post/{id}', name: 'post_show')]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    // PAGE DISPLAYING THE FORM POST FOR EDITION
    #[Route('/post/{id}/edit', name: 'post_edit')]
    public function editAction(
        Post $post,
        Request $request,
    ): Response
    {
        // Check for "authorize" access: calls all voters.
        $this->denyAccessUnlessGranted('authorize', $post);
        //Recover the connected user
        /** @var User $user */
        $user = $this->getUser();

        // Filters the content of logged-in user
        $filteredArtists = $user->getArtists();
        $filteredCategories = $user->getCategories();

        $form = $this->createForm(
            PostType::class,
            $post, 
            [
                'validation_groups' => ['Default', 'add'],
                'filtered_Artists' => $filteredArtists,
                'filtered_Categories' => $filteredCategories,
            ]
        )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if at least one media is added
            if ($post->getImages()->isEmpty() && $post->getVideos()->isEmpty()) {
                $this->addFlash(
                    'error',
                    'Please choose at least one media (image or video).'
                );
                return $this->redirectToRoute('post_edit', ['id' => $post->getId()]);
            }

            // Check we don't have two fields (File & url) filled for 1 video
            foreach ($post->getVideos() as $video) {
                if ($video->getFile() && $video->getUrl()) {
                    $this->addFlash(
                        'error',
                        'Please fill only one of the "File" or "URL" fields for each video.'
                    );
                    return $this->redirectToRoute('post_edit', ['id' => $post->getId()]);
                }
            }

            // If user choose to enter an url
            // verify that it's a Youtube url only
            foreach ($post->getVideos() as $video) {
                $urlVideo = $video->getUrl();
                if (!empty($urlVideo) && 
                    !str_starts_with($urlVideo, "https://www.youtube.com/watch?v=") &&
                    !str_starts_with($urlVideo, "www.youtube.com/watch?v=") &&
                    !str_starts_with($urlVideo, "youtube.com/watch?v=")) {
                        $this->addFlash(
                            'error',
                            'Please choose a Youtube URL only.'
                        );
                    return $this->redirectToRoute('post_edit', ['id' => $post->getId()]);
                }
            }

            // If all previous validations pass, proceed with post creation
            $this->handlePost->editPost($post);
            $this->addFlash(
                'success',
                'The post has been modified successfully.'
            );
            // Redirect to the modified post
            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }
        return $this->render('post/edit.html.twig', [
            'postForm' => $form->createView(),
            'filtered_Artists' => $filteredArtists,
            'filtered_Categories' => $filteredCategories,
        ]);
    }

    // METHOD TO DELETE A POST
    #[Route('/post/{id}/delete', name: 'post_delete')]
    public function deleteTaskAction(
        Post $post,
    ): RedirectResponse {
        // Check for "authorize" access: calls all voters.
        $this->denyAccessUnlessGranted('authorize', $post);

        $this->handlePost->deletePost($post);

        $this->addFlash(
            'success',
            'The post has been deleted successfully.'
        );
        return $this->redirectToRoute('app_posts');
    }
}
