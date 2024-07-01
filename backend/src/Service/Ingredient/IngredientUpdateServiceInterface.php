<?php

namespace App\Service\Ingredient;

use App\Entity\Ingredient\Ingredient;

interface IngredientUpdateServiceInterface
{
    public function execute(Ingredient $ingredient, array $ingredientData): array;
}
