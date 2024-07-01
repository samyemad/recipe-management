<?php

namespace App\Service\Recipe;

interface RecipeDetailServiceInterface
{
    public function execute(int $id): array;
}
