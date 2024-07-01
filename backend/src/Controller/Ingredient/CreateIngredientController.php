<?php

namespace App\Controller\Ingredient;

use App\Constants\Ingredient\IngredientSerializationGroups;
use App\Service\Ingredient\IngredientCreationServiceInterface;
use App\Trait\SerializerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CreateIngredientController extends AbstractController
{
    use SerializerTrait;

    #[Route('/', name: 'ingredient_create', methods: ['POST'])]
    public function __invoke(
        Request $request,
        IngredientCreationServiceInterface $ingredientCreationService,
        SerializerInterface $serializer
    ): Response {
        $data = json_decode($request->getContent(), true);
        $createResult = $ingredientCreationService->execute(
            $data
        );
        if ($createResult['success']) {
            return $this->serializeData(
                $serializer,
                $createResult['ingredient'],
                IngredientSerializationGroups::INGREDIENT_WRITE,
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
