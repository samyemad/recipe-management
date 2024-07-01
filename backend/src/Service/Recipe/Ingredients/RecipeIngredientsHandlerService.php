<?php

namespace App\Service\Recipe\Ingredients;

use App\Constants\Recipe\Ingredients\RecipeIngredientsAction;
use App\DTO\Recipe\RecipeIngredientsDTO;
use App\Entity\Recipe\Recipe;
use App\Entity\Recipe\RecipeIngredient;
use App\Exception\Recipe\Ingredients\InvalidRecipeIngredientsActionException;
use Doctrine\ORM\EntityManagerInterface;

class RecipeIngredientsHandlerService implements RecipeIngredientsHandlerServiceInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws InvalidRecipeIngredientsActionException
     */
    public function execute(
        RecipeIngredientsDTO $recipeIngredientsDTO,
        Recipe $recipe
    ): void {
        switch ($recipeIngredientsDTO->getAction()) {
            case RecipeIngredientsAction::ADD:
                $newIngredient = new RecipeIngredient();
                $newIngredient->setIngredient($recipeIngredientsDTO->getIngredient());
                $newIngredient->setQuantity($recipeIngredientsDTO->getQuantity());
                $newIngredient->setRecipe($recipe);
                $this->entityManager->persist($newIngredient);
                break;
            case RecipeIngredientsAction::UPDATE:
                $existingIngredient = $recipe->getRecipeIngredients()->filter(function ($ri) use ($recipeIngredientsDTO) {
                    return $ri->getIngredient() === $recipeIngredientsDTO->getIngredient();
                })->first();
                if ($existingIngredient) {
                    $existingIngredient->setQuantity($recipeIngredientsDTO->getQuantity());
                }
                break;
            case RecipeIngredientsAction::REMOVE:
                $toRemove = $recipe->getRecipeIngredients()->filter(function ($ri) use ($recipeIngredientsDTO) {
                    return $ri->getIngredient() === $recipeIngredientsDTO->getIngredient();
                })->first();
                if ($toRemove) {
                    $this->entityManager->remove($toRemove);
                }
                break;
            default:
                throw new InvalidRecipeIngredientsActionException($recipeIngredientsDTO->getAction());
        }
    }
}
