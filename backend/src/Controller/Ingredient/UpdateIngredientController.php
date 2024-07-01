<?php

namespace App\Controller\Ingredient;

use App\Repository\Ingredient\IngredientRepository;
use App\Service\Ingredient\IngredientUpdateServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateIngredientController extends AbstractController
{
    #[Route('/{id}', name: 'ingredient_update', methods: ['PUT'])]
    public function __invoke(
        int $id,
        Request $request,
        IngredientRepository $ingredientRepository,
        IngredientUpdateServiceInterface $ingredientUpdateService
    ): Response {
        $ingredient = $ingredientRepository->find($id);
        if (! $ingredient) {
            throw $this->createNotFoundException('Ingredient not found');
        }
        $updateResult = $ingredientUpdateService->execute(
            $ingredient,
            json_decode($request->getContent(), true)
        );

        if ($updateResult['success']) {
            return $this->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->json([
                'status' => 'error',
                'errors' => $updateResult['errors'],
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
