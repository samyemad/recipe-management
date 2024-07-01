<?php

namespace App\Controller\Recipe;

use App\Repository\Recipe\RecipeRepository;
use App\Service\Recipe\RecipeDeletionServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteRecipeController extends AbstractController
{
    #[Route('/{id}', name: 'recipe_delete', methods: ['DELETE'])]
    public function __invoke(
        int $id,
        RecipeRepository $recipeRepository,
        RecipeDeletionServiceInterface $recipeDeletionService
    ): Response {
        $recipe = $recipeRepository->find($id);
        if (! $recipe) {
            throw $this->createNotFoundException('Recipe not found');
        }
        $recipeDeletionService->execute($recipe);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
