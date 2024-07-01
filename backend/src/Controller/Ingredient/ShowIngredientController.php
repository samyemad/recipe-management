<?php

namespace App\Controller\Ingredient;

use App\Constants\Ingredient\IngredientSerializationGroups;
use App\Service\Ingredient\IngredientDetailServiceInterface;
use App\Trait\SerializerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ShowIngredientController extends AbstractController
{
    use SerializerTrait;

    #[Route('/{id}', name: 'ingredient_show', methods: ['GET'])]
    public function __invoke(
        int $id,
        SerializerInterface $serializer,
        IngredientDetailServiceInterface $ingredientDetailService
    ): Response {
        $result = $ingredientDetailService->execute($id);

        if ($result['success']) {
            return $this->serializeData(
                $serializer,
                $result['ingredient'],
                IngredientSerializationGroups::INGREDIENT_READ,
                []
            );
        }

        return $this->json([
            'success' => false,
            'errors' => $result['errors'],
        ], Response::HTTP_NOT_FOUND);
    }
}
