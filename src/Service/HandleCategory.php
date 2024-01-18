<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;

/**
 * HandleCategory class
 */
final class HandleCategory implements HandleCategoryInterface
{
    /**
     * HandleTask constructor
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        private EntityManagerInterface $manager
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
     * @param Task $task
     * @return void
     */
    public function deleteCategory(Category $category): void
    {
        $this->manager->remove($category);
        $this->manager->flush();
    }
}