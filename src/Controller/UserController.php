<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserDataInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    /**
     * UserController constructor
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        private UserRepository $userRepository
    ) {
    }
    
    #[Route('/users', name: 'user_list')]
    public function listAction(): Response
    {
        return $this->render('user/list.html.twig', [
            'users' => $this->userRepository->findAll()
        ]);
    }

    #[Route('/user/{id}/edit', name: 'user_edit')]
    public function editAction(
        User $user,
        Request $request,
        UserDataInterface $userData
    ): Response {
        $form = $this->createForm(
            UserType::class,
            $user
        )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userData->editUser($user);

            $this->addFlash(
                'success',
                "User data successfully updated"
            );

            return $this->redirectToRoute('user_list');
        }

        return $this->render(
            'user/edit.html.twig',
            ['form' => $form->createView(), 'user' => $user]
        );
    }

    #[Route('/user/{id}/delete', name: 'user_delete')]
    public function deleteUserAction(
        User $user,
        UserDataInterface $userData
    ): RedirectResponse {
        $userData->deleteUser($user);

        $this->addFlash(
            'success',
            'User successfully deleted'
        );

        return $this->redirectToRoute('user_list');
    }
}
