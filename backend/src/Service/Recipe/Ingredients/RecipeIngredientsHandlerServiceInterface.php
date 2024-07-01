<?php

namespace App\Service\Recipe\Ingredients;

use App\DTO\Recipe\RecipeIngredientsDTO;
use App\Entity\Recipe\Recipe;

interface RecipeIngredientsHandlerServiceInterface
{
    public function execute(RecipeIngredientsDTO $recipeIngredientsDTO, Recipe $recipe): void;
}
