<?php

namespace App\Service\Ingredient;

use App\Entity\Ingredient\Ingredient;
use App\Form\Ingredient\IngredientType;
use App\Trait\FormErrorsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class IngredientUpdateService implements IngredientUpdateServiceInterface
{
    use FormErrorsTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FormFactoryInterface $formFactory
    ) {
    }

    public function execute(
        Ingredient $ingredient,
        array $ingredientData
    ): array {
        $form = $this->formFactory->create(
            IngredientType::class,
            $ingredient
        );
        $form->submit($ingredientData);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return ['success' => true];
        }

        return ['success' => false, 'errors' => $this->getFormErrors($form)];
    }
}
