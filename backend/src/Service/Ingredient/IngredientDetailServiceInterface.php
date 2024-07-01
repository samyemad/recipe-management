<?php

namespace App\Service\Ingredient;

interface IngredientDetailServiceInterface
{
    public function execute(int $id): array;
}
