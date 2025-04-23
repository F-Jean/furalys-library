<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\User;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use App\Service\HandleArtistInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ArtistController extends AbstractController
{
    /**
     * ArtistController constructor
     *
     * @param ArtistRepository $artistRepository
     */
    public function __construct(
        private ArtistRepository $artistRepository,
        private SluggerInterface $slugger,
        private HandleArtistInterface $handleArtist,
    ) {
    }

    #[Route('/artists', name: 'artist_list')]
    public function listAction(): Response
    {
        //Recover the connected user
        $user = $this->getUser();

        // Check that the user is connected
        if (!$user instanceof User) {
            throw new AccessDeniedException('You have to connect first.');
        } else {
            // If user is connected, recover THEIR categories
            $artists = $this->artistRepository->findBy(['user' => $user]);
        }

        return $this->render('artist/list.html.twig', [
            'artists' => $artists ?? [],
            'user' => $user
        ]);
    }

    #[Route('/artist/create', name: 'artist_create')]
    public function createAction(
        Request $request,
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $artist = new Artist();
        $artist->setUser($user);

        $form = $this->createForm(
            ArtistType::class,
            $artist
        )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artist->setSlug($this->slugger->slug($artist->getName())->lower()->toString());

            $this->handleArtist->createArtist($artist);

            $this->addFlash(
                'success',
                'The artist has been created successfully.'
            );

            return $this->redirectToRoute('artist_list');
        }

        return $this->render('artist/create.html.twig', [
            'form' => $form
        ]);
    }

    // Keep below the create method otherwise they enter in conflicts
    #[Route('/artist/{id}', name: 'artist_show')]
    public function showAction(Artist $artist): Response
    {
        //Recover the connected user
        $user = $this->getUser();

        return $this->render('artist/show.html.twig', [
            'artist' => $artist,
            'user' => $user
        ]);
    }

    #[Route('/artist/{id}/edit', name: 'artist_edit')]
    public function editAction(
        Artist $artist,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted('authorize', $artist);

        $form = $this->createForm(
            ArtistType::class,
            $artist
        )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artist->setSlug($this->slugger->slug($artist->getName())->lower()->toString());

            $this->handleArtist->editArtist($artist);

            $this->addFlash(
                'success',
                'The artist has been modified successfully !'
            );

            return $this->redirectToRoute('artist_list');
        }

        return $this->render('artist/edit.html.twig', [
            'form' => $form->createView(),
            'artist' => $artist,
        ]);
    }

    #[Route('/artist/{id}/delete', name: 'artist_delete')]
    public function deleteAction(
        Artist $artist,
    ): RedirectResponse {
        // Check that the user is logged in, that's an Artist object and
        // only the author/user logged or admin can manage it
        $this->denyAccessUnlessGranted('authorize', $artist);

        $this->handleArtist->deleteArtist($artist);

        $this->addFlash(
            'success',
            'The artist has been deleted successfully !'
        );

        return $this->redirectToRoute('artist_list');
    }
}
