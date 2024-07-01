<?php

namespace App\Service\Recipe\Ingredients;

use App\Entity\Recipe\Recipe;

interface RecipeIngredientsManagerServiceInterface
{
    public function execute(Recipe $recipe, array $ingredientOperations): array;
}
