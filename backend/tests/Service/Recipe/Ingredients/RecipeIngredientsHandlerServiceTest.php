<?php

namespace App\Tests\Service\Recipe\Ingredients;

use App\Constants\Recipe\Ingredients\RecipeIngredientsAction;
use App\DTO\Recipe\RecipeIngredientsDTO;
use App\Entity\Ingredient\Ingredient;
use App\Entity\Recipe\Recipe;
use App\Entity\Recipe\RecipeIngredient;
use App\Exception\Recipe\Ingredients\InvalidRecipeIngredientsActionException;
use App\Service\Recipe\Ingredients\RecipeIngredientsHandlerService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class RecipeIngredientsHandlerServiceTest extends TestCase
{
    private readonly EntityManagerInterface $entityManager;
    private readonly RecipeIngredientsHandlerService $service;
    private readonly Recipe $recipe;
    private readonly Ingredient $ingredient;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->service = new RecipeIngredientsHandlerService($this->entityManager);
        $this->recipe = new Recipe();
        $this->ingredient = new Ingredient();
    }

    /**
     * @throws Exception
     * @throws InvalidRecipeIngredientsActionException
     */
    public function testAddIngredient(): void
    {
        $this->entityManager->expects($this->once())->method('persist');

        $recipeIngredientsDTO = $this->createRecipeIngredientsDTO(RecipeIngredientsAction::ADD, 10);
        $this->service->execute($recipeIngredientsDTO, $this->recipe);
    }

    /**
     * @throws InvalidRecipeIngredientsActionException
     */
    public function testUpdateIngredient(): void
    {
        $recipeIngredient = $this->createRecipeIngredient(5);
        $this->recipe->addRecipeIngredient($recipeIngredient);

        $recipeIngredientsDTO = $this->createRecipeIngredientsDTO(RecipeIngredientsAction::UPDATE, 10);
        $this->service->execute($recipeIngredientsDTO, $this->recipe);

        $this->assertEquals(10, $recipeIngredient->getQuantity());
    }

    /**
     * @throws InvalidRecipeIngredientsActionException
     * @throws Exception
     */
    public function testRemoveIngredient(): void
    {
        $this->entityManager->expects($this->once())->method('remove');

        $recipeIngredient = $this->createRecipeIngredient();
        $this->recipe->addRecipeIngredient($recipeIngredient);

        $recipeIngredientsDTO = $this->createRecipeIngredientsDTO(RecipeIngredientsAction::REMOVE);
        $this->service->execute($recipeIngredientsDTO, $this->recipe);
    }

    public function testInvalidActionThrowsException(): void
    {
        $this->expectException(InvalidRecipeIngredientsActionException::class);

        $recipeIngredientsDTO = $this->createRecipeIngredientsDTO('invalid_action');
        $this->service->execute($recipeIngredientsDTO, $this->recipe);
    }

    private function createRecipeIngredientsDTO(string $action, ?int $quantity = null): RecipeIngredientsDTO
    {
        $recipeIngredientsDTO = new RecipeIngredientsDTO();
        $recipeIngredientsDTO->setAction($action);
        $recipeIngredientsDTO->setIngredient($this->ingredient);
        if (null !== $quantity) {
            $recipeIngredientsDTO->setQuantity($quantity);
        }

        return $recipeIngredientsDTO;
    }

    private function createRecipeIngredient(?int $quantity = null): RecipeIngredient
    {
        $recipeIngredient = new RecipeIngredient();
        $recipeIngredient->setIngredient($this->ingredient);
        if (null !== $quantity) {
            $recipeIngredient->setQuantity($quantity);
        }

        return $recipeIngredient;
    }
}
