<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Exception\MultipleThumbnailsException;
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
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

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

    // PAGE DISPLAYING ALL THE THUMBNAILS POSTS
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

            // If all previous validations pass, proceed with post creation
            try {
                $this->handlePost->createPost($post);
            } catch (MultipleThumbnailsException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('post_create');
            }
            
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
        if ($post->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

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

            // If all previous validations pass, proceed with post creation
            try {
                $this->handlePost->editPost($post);
            } catch (MultipleThumbnailsException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('post_edit', ['id' => $post->getId()]);
            }
            
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

    // METHODS TO SWITCH THE THUMBNAIL OF A POST DIRECTLY FROM SHOW PAGE
    #[Route('/post/{postId}/image/{imageId}/set-thumbnail', name: 'post_set_thumbnail', methods: ['POST'])]
    public function setThumbnail(
        int $postId, 
        int $imageId,
        Request $request,
        CsrfTokenManagerInterface $csrfTokenManager
    ): RedirectResponse
    {
        $post = $this->postRepository->find($postId);

        if (!$post || $post->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You are not allowed to modify this post.');
        }

        $submittedToken = $request->request->get('_token');
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('set-thumbnail-' . $imageId, $submittedToken))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        try {
            $this->handlePost->setThumbnailImage($post, $imageId);
            $this->addFlash('success', 'Thumbnail updated successfully.');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('post_show', ['id' => $postId]);
    }

    #[Route('/post/{postId}/video/{videoId}/set-thumbnail', name: 'post_set_video_thumbnail', methods: ['POST'])]
    public function setVideoThumbnail(
        int $postId, 
        int $videoId,
        Request $request,
        CsrfTokenManagerInterface $csrfTokenManager
    ): RedirectResponse
    {
        $post = $this->postRepository->find($postId);

        if (!$post || $post->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You are not allowed to modify this post.');
        }

        $submittedToken = $request->request->get('_token');
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('set-thumbnail-video-' . $videoId, $submittedToken))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        try {
            $this->handlePost->setThumbnailVideo($post, $videoId);
            $this->addFlash('success', 'Video thumbnail updated successfully.');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('post_show', ['id' => $postId]);
    }
}
