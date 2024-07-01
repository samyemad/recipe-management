<?php

namespace App\Controller\Recipe;

use App\Repository\Recipe\RecipeRepository;
use App\Service\Recipe\RecipeUpdateServiceInterface;
use App\Trait\FormErrorsTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateRecipeController extends AbstractController
{
    use FormErrorsTrait;

    #[Route('/{id}', name: 'recipe_update', methods: ['PUT'])]
    public function __invoke(
        int $id,
        RecipeRepository $recipeRepository,
        RecipeUpdateServiceInterface $recipeUpdateService,
        Request $request
    ): Response {
        $recipe = $recipeRepository->find($id);
        if (! $recipe) {
            throw $this->createNotFoundException('Recipe not found');
        }

        $updateResult = $recipeUpdateService->execute(
            $recipe,
            json_decode($request->getContent(), true)
        );

        if ($updateResult['success']) {
            return $this->json(null, Response::HTTP_NO_CONTENT);
        }

        return $this->json([
            'status' => 'error',
            'errors' => $updateResult['errors'],
        ], Response::HTTP_BAD_REQUEST);
    }
}
