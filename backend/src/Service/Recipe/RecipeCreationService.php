<?php

namespace App\Service\Recipe;

use App\Entity\Recipe\Recipe;
use App\Form\Recipe\RecipeType;
use App\Trait\FormErrorsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class RecipeCreationService implements RecipeCreationServiceInterface
{
    use FormErrorsTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FormFactoryInterface $formFactory
    ) {
    }

    public function execute(array $recipeData): array
    {
        $recipe = new Recipe();
        $form = $this->formFactory->create(RecipeType::class, $recipe);
        $form->submit($recipeData);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($recipe);
            $this->entityManager->flush();

            return ['success' => true, 'recipe' => $recipe];
        }

        return ['success' => false, 'errors' => $this->getFormErrors($form)];
    }
}
