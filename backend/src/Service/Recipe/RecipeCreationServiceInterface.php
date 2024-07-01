<?php

namespace App\Service\Recipe;

interface RecipeCreationServiceInterface
{
    public function execute(array $recipeData): array;
}
