<?php

namespace App\Controller\Ingredient;

use App\Constants\Ingredient\IngredientSerializationGroups;
use App\Service\Ingredient\IngredientListingServiceInterface;
use App\Trait\SerializerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ListIngredientsController extends AbstractController
{
    use SerializerTrait;

    #[Route('/', name: 'ingredient_index', methods: ['GET'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        IngredientListingServiceInterface $ingredientListingService
    ): Response {
        $limit = (int) $request->query->get('limit', 10); // Default limit to 10
        $offset = (int) $request->query->get('offset', 0); // Default offset to 0

        $ingredients = $ingredientListingService->execute($limit, $offset);

        return $this->serializeData(
            $serializer,
            $ingredients,
            IngredientSerializationGroups::INGREDIENT_READ
        );
    }
}
