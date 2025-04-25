<?php

namespace App\Service;

use App\Entity\Category;

/**
 * HandleCategoryInterface interface
 */
interface HandleCategoryInterface
{
    public function createCategory(Category $category): void;
    public function editCategory(): void;
    public function deleteCategory(Category $category): void;
}