<?php

namespace App\DTO\Recipe;

use App\Entity\Ingredient\Ingredient;

class RecipeIngredientsDTO
{
    private Ingredient $ingredient;
    private string $action;
    private ?int $quantity = null;

    public function __construct(
    ) {
    }

    public function getIngredient(): Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(Ingredient $ingredient): void
    {
        $this->ingredient = $ingredient;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
