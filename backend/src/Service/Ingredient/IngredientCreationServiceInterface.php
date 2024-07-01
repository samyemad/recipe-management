<?php

namespace App\Service\Ingredient;

interface IngredientCreationServiceInterface
{
    public function execute(array $ingredientData): array;
}
