<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;

/**
 * HandleCategoryInterface interface
 */
interface HandleCategoryInterface
{
    public function __construct(EntityManagerInterface $manager);
    public function createCategory(Category $category): void;
    public function editCategory(): void;
    public function deleteCategory(Category $category): void;
}