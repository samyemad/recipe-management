<?php

namespace App\Service\Recipe;

use App\Entity\Recipe\Recipe;

interface RecipeUpdateServiceInterface
{
    public function execute(Recipe $recipe, array $data): array;
}
