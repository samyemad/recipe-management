<?php

namespace App\Service\Recipe;

use App\Entity\Recipe\Recipe;
use Doctrine\ORM\EntityManagerInterface;

class RecipeDeletionService implements RecipeDeletionServiceInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function execute(Recipe $recipe): void
    {
        $this->entityManager->remove($recipe);
        $this->entityManager->flush();
    }
}
