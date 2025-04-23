<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Service\HandleCategoryInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class CategoryController extends AbstractController
{
    /**
     * CategoryController constructor
     *
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        private CategoryRepository $categoryRepository,
        private SluggerInterface $slugger,
        private HandleCategoryInterface $handleCategory
    ) {
    }

    #[Route('/categories', name: 'category_list')]
    public function listAction(): Response
    {
        //Recover the connected user
        $user = $this->getUser();

        // Check that the user is connected
        if (!$user instanceof User) {
            throw new AccessDeniedException('You have to connect first.');
        } else {
            // If user is connected, recover THEIR categories
            $categories = $this->categoryRepository->findBy(['user' => $user]);
        }

        return $this->render('category/list.html.twig', [
            // if $categories is defined and not null then 'categories' => $categories is used
            // if $categories is null then 'categories' => [] is used
            'categories' => $categories ?? [],
            'user' => $user
        ]);
    }

    #[Route('/category/create', name: 'category_create')]
    public function createAction(
        Request $request,
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $category = new Category();
        $category->setUser($user);

        $form = $this->createForm(
            CategoryType::class,
            $category
        )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug($this->slugger->slug($category->getTitle())->lower()->toString());

            $this->handleCategory->createCategory($category);

            $this->addFlash(
                'success',
                'The category has been created successfully.'
            );

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/create.html.twig', [
            'form' => $form
        ]);
    }
    
    #[Route('/category/{id}/edit', name: 'category_edit')]
    public function editAction(
        Category $category,
        Request $request,
    ): Response {
        // Check for "authorize" access: calls all voters.
        $this->denyAccessUnlessGranted('authorize', $category);

        $form = $this->createForm(
            CategoryType::class,
            $category
        )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug($this->slugger->slug($category->getTitle())->lower()->toString());

            $this->handleCategory->editCategory();

            $this->addFlash(
                'success',
                'The category has been modified successfully !'
            );

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    #[Route('/category/{id}/delete', name: 'category_delete')]
    public function deleteTaskAction(
        Category $category,
    ): RedirectResponse {
        $this->denyAccessUnlessGranted('authorize', $category);

        $this->handleCategory->deleteCategory($category);

        $this->addFlash(
            'success',
            'The category has been deleted successfully.'
        );
        return $this->redirectToRoute('category_list');
    }
}
