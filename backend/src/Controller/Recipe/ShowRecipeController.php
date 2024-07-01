<?php

namespace App\Controller\Recipe;

use App\Constants\Ingredient\RecipeSerializationGroups;
use App\Service\Recipe\RecipeDetailServiceInterface;
use App\Trait\SerializerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ShowRecipeController extends AbstractController
{
    use SerializerTrait;

    #[Route('/{id}', name: 'recipe_show', methods: ['GET'])]
    public function __invoke(
        int $id,
        SerializerInterface $serializer,
        RecipeDetailServiceInterface $recipeDetailService
    ): Response {
        $result = $recipeDetailService->execute($id);

        if ($result['success']) {
            return $this->serializeData(
                $serializer,
                $result['recipe'],
                RecipeSerializationGroups::RECIPE_READ,
                []
            );
        }

        return $this->json([
            'success' => false,
            'errors' => $result['errors'],
        ], Response::HTTP_NOT_FOUND);
    }
}
