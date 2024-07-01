<?php

namespace App\Service\Ingredient;

use App\Repository\Ingredient\IngredientRepository;

class IngredientDetailService implements IngredientDetailServiceInterface
{
    public function __construct(private readonly IngredientRepository $ingredientRepository)
    {
    }

    public function execute(int $id): array
    {
        $ingredient = $this->ingredientRepository->find($id);

        if (! $ingredient) {
            return ['success' => false, 'errors' => 'Ingredient not found'];
        }

        return ['success' => true, 'ingredient' => $ingredient];
    }
}
