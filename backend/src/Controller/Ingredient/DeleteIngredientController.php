<?php

namespace App\Controller\Ingredient;

use App\Repository\Ingredient\IngredientRepository;
use App\Service\Ingredient\IngredientDeletionServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteIngredientController extends AbstractController
{
    #[Route('/{id}', name: 'ingredient_delete', methods: ['DELETE'])]
    public function __invoke(
        int $id,
        IngredientRepository $ingredientRepository,
        IngredientDeletionServiceInterface $ingredientDeletionService
    ): Response {
        $ingredient = $ingredientRepository->find($id);
        if (! $ingredient) {
            throw $this->createNotFoundException('Ingredient not found');
        }
        $ingredientDeletionService->execute($ingredient);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
