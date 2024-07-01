<?php

namespace App\Controller\Recipe;

use App\Constants\Ingredient\RecipeSerializationGroups;
use App\Service\Recipe\RecipeListingServiceInterface;
use App\Trait\SerializerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ListRecipesController extends AbstractController
{
    use SerializerTrait;

    #[Route('/', name: 'recipe_index', methods: ['GET'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        RecipeListingServiceInterface $recipeListingService
    ): Response {
        $limit = (int) $request->query->get('limit', 10);
        $offset = (int) $request->query->get('offset', 0);

        $recipes = $recipeListingService->execute($limit, $offset);

        return $this->serializeData($serializer, $recipes, RecipeSerializationGroups::RECIPE_READ);
    }
}
