<?php

namespace App\Service\Recipe;

use App\Repository\Recipe\RecipeRepository;

class RecipeDetailService implements RecipeDetailServiceInterface
{
    public function __construct(private readonly RecipeRepository $recipeRepository)
    {
    }

    public function execute(int $id): array
    {
        $recipe = $this->recipeRepository->find($id);

        if (! $recipe) {
            return ['success' => false, 'errors' => 'Recipe not found'];
        }

        return ['success' => true, 'recipe' => $recipe];
    }
}
