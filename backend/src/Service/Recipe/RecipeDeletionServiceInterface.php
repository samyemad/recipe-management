<?php

namespace App\Service\Recipe;

use App\Entity\Recipe\Recipe;

interface RecipeDeletionServiceInterface
{
    public function execute(Recipe $recipe): void;
}
