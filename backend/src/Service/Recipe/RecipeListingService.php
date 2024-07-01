<?php

namespace App\Service\Recipe;

use App\Repository\Recipe\RecipeRepository;

class RecipeListingService implements RecipeListingServiceInterface
{
    public function __construct(private readonly RecipeRepository $recipeRepository)
    {
    }

    public function execute(int $limit, int $offset): array
    {
        return $this->recipeRepository->findWithPagination($limit, $offset);
    }
}
