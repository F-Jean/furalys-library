<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;

/**
 * HandleCategory class
 * 
 * Category management service
 */
final class HandleCategory implements HandleCategoryInterface
{
    /**
     * HandleTask constructor
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        private readonly EntityManagerInterface $manager
    ) {
    }

    /**
     * @param Category $category
     * @return void
     */
    public function createCategory(Category $category): void
    {
        $this->manager->persist($category);
        $this->manager->flush();
    }

    /**
     * @return void
     */
    public function editCategory(): void
    {
        $this->manager->flush();
    }

    /**
     * @param Category $category
     * @return void
     */
    public function deleteCategory(Category $category): void
    {
        $this->manager->remove($category);
        $this->manager->flush();
    }
}