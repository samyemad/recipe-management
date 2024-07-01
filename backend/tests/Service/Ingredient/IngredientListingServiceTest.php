<?php

namespace App\Tests\Service\Ingredient;

use App\Entity\Ingredient\Ingredient;
use App\Repository\Ingredient\IngredientRepository;
use App\Service\Ingredient\IngredientListingService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class IngredientListingServiceTest extends TestCase
{
    private readonly IngredientRepository $ingredientRepository;
    private readonly IngredientListingService $service;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->ingredientRepository = $this->createMock(IngredientRepository::class);
        $this->service = new IngredientListingService($this->ingredientRepository);
    }

    public function testExecuteReturnsIngredientsWithPagination(): void
    {
        $ingredient1 = new Ingredient();
        $ingredient2 = new Ingredient();
        $this->ingredientRepository->method('findWithPagination')->willReturn([$ingredient1, $ingredient2]);
        $limit = 10;
        $offset = 0;
        $result = $this->service->execute($limit, $offset);

        $this->assertCount(2, $result);
        $this->assertSame([$ingredient1, $ingredient2], $result);
    }

    public function testExecuteReturnsEmptyArrayWithPagination(): void
    {
        $this->ingredientRepository->method('findWithPagination')->willReturn([]);
        $limit = 10;
        $offset = 0;
        $result = $this->service->execute($limit, $offset);
        $this->assertEmpty($result);
    }
}
