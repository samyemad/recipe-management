<?php

namespace App\Service\Ingredient;

use App\Entity\Ingredient\Ingredient;
use App\Form\Ingredient\IngredientType;
use App\Trait\FormErrorsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class IngredientCreationService implements IngredientCreationServiceInterface
{
    use FormErrorsTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FormFactoryInterface $formFactory
    ) {
    }

    public function execute(array $ingredientData): array
    {
        $ingredient = new Ingredient();
        $form = $this->formFactory->create(IngredientType::class, $ingredient);
        $form->submit($ingredientData);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($ingredient);
            $this->entityManager->flush();

            return ['success' => true, 'ingredient' => $ingredient];
        }

        return ['success' => false, 'errors' => $this->getFormErrors($form)];
    }
}
