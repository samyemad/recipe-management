<?php

namespace App\Tests\Service\Ingredient;

use App\Entity\Ingredient\Ingredient;
use App\Service\Ingredient\IngredientDeletionService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class IngredientDeletionServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testExecute()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entityManager->expects($this->once())
            ->method('remove')
            ->with($this->isInstanceOf(Ingredient::class));
        $entityManager->expects($this->once())
            ->method('flush');

        $service = new IngredientDeletionService($entityManager);

        $ingredient = new Ingredient();

        $service->execute($ingredient);
    }
}
