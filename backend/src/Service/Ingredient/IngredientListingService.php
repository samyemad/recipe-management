<?php

namespace App\Service\Ingredient;

use App\Repository\Ingredient\IngredientRepository;

class IngredientListingService implements IngredientListingServiceInterface
{
    public function __construct(private readonly IngredientRepository $ingredientRepository)
    {
    }

    public function execute(int $limit, int $offset): array
    {
        return $this->ingredientRepository->findWithPagination($limit, $offset);
    }
}
