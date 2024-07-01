<?php

namespace App\Controller\Recipe\Ingredients;

use App\Entity\Recipe\Recipe;
use App\Service\Recipe\Ingredients\RecipeIngredientsManagerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManageRecipeIngredientsController extends AbstractController
{
    #[Route('/{recipeId}/ingredients', methods: ['PATCH'])]
    public function __invoke(
        int $recipeId,
        Request $request,
        EntityManagerInterface $entityManager,
        RecipeIngredientsManagerServiceInterface $recipeIngredientsManagerService
    ): Response {
        $recipe = $entityManager->getRepository(Recipe::class)->find($recipeId);
        if (! $recipe) {
            return $this->json(['error' => 'Recipe not found'], Response::HTTP_NOT_FOUND);
        }
        $data = json_decode($request->getContent(), true);
        $ingredientOperations = $data['operations'] ?? [];
        if (empty($ingredientOperations)) {
            return $this->json(['error' => 'No operations provided'], Response::HTTP_BAD_REQUEST);
        }
        $result = $recipeIngredientsManagerService->execute(
            $recipe,
            $ingredientOperations
        );
        if ($result['success']) {
            return $this->json(null, Response::HTTP_NO_CONTENT);
        }

        return $this->json([
            'status' => 'error',
            'errors' => $result['errors'],
        ], Response::HTTP_BAD_REQUEST);
    }
}
