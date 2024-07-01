<?php

namespace App\Entity\Recipe;

use App\Constants\Ingredient\RecipeSerializationGroups;
use App\Entity\Ingredient\Ingredient;
use App\Repository\Recipe\RecipeIngredientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecipeIngredientRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_recipe_ingredient', columns: ['recipe_id', 'ingredient_id'])]
class RecipeIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Recipe::class, inversedBy: 'recipeIngredients')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private Recipe $recipe;

    #[ORM\ManyToOne(targetEntity: Ingredient::class, inversedBy: 'recipeIngredients')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    #[Groups([
        RecipeSerializationGroups::RECIPE_READ,
        RecipeSerializationGroups::RECIPE_WRITE,
    ])]
    private Ingredient $ingredient;

    #[ORM\Column(type: 'integer')]
    #[Groups([
        RecipeSerializationGroups::RECIPE_READ,
    ])]
    private int $quantity;

    public function getId(): int
    {
        return $this->id;
    }

    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getIngredient(): Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
