<?php

namespace App\Service\Ingredient;

use App\Entity\Ingredient\Ingredient;

interface IngredientDeletionServiceInterface
{
    public function execute(Ingredient $ingredient): void;
}
