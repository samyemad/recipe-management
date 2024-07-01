<?php

namespace App\Service\Recipe;

use App\Entity\Recipe\Recipe;
use App\Form\Recipe\RecipeType;
use App\Trait\FormErrorsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class RecipeUpdateService implements RecipeUpdateServiceInterface
{
    use FormErrorsTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FormFactoryInterface $formFactory
    ) {
    }

    public function execute(Recipe $recipe, array $data): array
    {
        $form = $this->formFactory->create(RecipeType::class, $recipe);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return ['success' => true];
        }

        return ['success' => false, 'errors' => $this->getFormErrors($form)];
    }
}
