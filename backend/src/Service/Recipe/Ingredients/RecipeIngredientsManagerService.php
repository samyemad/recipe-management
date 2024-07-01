<?php

namespace App\Service\Recipe\Ingredients;

use App\DTO\Recipe\RecipeIngredientsDTO;
use App\Entity\Recipe\Recipe;
use App\Form\Recipe\RecipeIngredientType;
use App\Trait\FormErrorsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class RecipeIngredientsManagerService implements RecipeIngredientsManagerServiceInterface
{
    use FormErrorsTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FormFactoryInterface $formFactory,
        private readonly RecipeIngredientsHandlerServiceInterface $recipeIngredientsHandlerService
    ) {
    }

    public function execute(Recipe $recipe, array $ingredientOperations): array
    {
        $this->entityManager->beginTransaction();
        try {
            foreach ($ingredientOperations as $operation) {
                $recipeIngredientDTO = new RecipeIngredientsDTO();
                $form = $this->formFactory->create(
                    RecipeIngredientType::class,
                    $recipeIngredientDTO
                );
                $form->submit($operation);
                if ($form->isSubmitted() && $form->isValid()) {
                    $this->recipeIngredientsHandlerService->execute(
                        $recipeIngredientDTO,
                        $recipe
                    );
                } else {
                    $this->entityManager->rollback();

                    return ['success' => false, 'errors' => $this->getFormErrors($form)];
                }
            }
            $this->entityManager->flush();
            $this->entityManager->commit();

            return ['success' => true];
        } catch (\Exception $e) {
            $this->entityManager->rollback();

            return ['success' => false, 'errors' => ['Exception' => $e->getMessage()]];
        }
    }
}
