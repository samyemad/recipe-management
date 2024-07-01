<?php

namespace App\Controller\Recipe;

use App\Constants\Ingredient\RecipeSerializationGroups;
use App\Service\Recipe\RecipeCreationServiceInterface;
use App\Trait\SerializerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CreateRecipeController extends AbstractController
{
    use SerializerTrait;

    #[Route('/', name: 'recipe_create', methods: ['POST'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        RecipeCreationServiceInterface $recipeCreationService
    ): Response {
        $data = json_decode($request->getContent(), true);
        $createResult = $recipeCreationService->execute($data);

        if ($createResult['success']) {
            return $this->serializeData(
                $serializer,
                $createResult['recipe'],
                RecipeSerializationGroups::RECIPE_WRITE,
                [],
                Response::HTTP_CREATED
            );
        }

        return $this->json(
            [
                'status' => 'error',
                'errors' => $createResult['errors'],
            ],
            Response::HTTP_BAD_REQUEST
        );
    }
}
