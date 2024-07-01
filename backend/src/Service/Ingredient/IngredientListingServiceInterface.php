<?php

namespace App\Service\Ingredient;

interface IngredientListingServiceInterface
{
    public function execute(int $limit, int $offset): array;
}
