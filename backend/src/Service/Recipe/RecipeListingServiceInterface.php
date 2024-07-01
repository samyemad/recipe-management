<?php

namespace App\Service\Recipe;

interface RecipeListingServiceInterface
{
    public function execute(int $limit, int $offset): array;
}
