<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserDataInterface;

class UserController extends AbstractController
{
    #[Route('/user/create', name: 'user_create')]
    public function createAction(
        Request $request,
        UserDataInterface $userData
    ): Response {
        $user = new User();
        $form = $this->createForm(
            UserType::class,
            $user
        )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userData->createUser($user);

            $this->addFlash(
                'success',
                "L'utilisateur a bien été ajouté."
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
