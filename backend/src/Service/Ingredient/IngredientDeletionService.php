<?php

namespace App\Service\Ingredient;

use App\Entity\Ingredient\Ingredient;
use Doctrine\ORM\EntityManagerInterface;

class IngredientDeletionService implements IngredientDeletionServiceInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function execute(Ingredient $ingredient): void
    {
        $this->entityManager->remove($ingredient);
        $this->entityManager->flush();
    }
}
