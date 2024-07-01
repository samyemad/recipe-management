<?php

namespace App\Tests\Service\Ingredient;

use App\Entity\Ingredient\Ingredient;
use App\Repository\Ingredient\IngredientRepository;
use App\Service\Ingredient\IngredientDetailService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class IngredientDetailServiceTest extends TestCase
{
    private readonly IngredientRepository $ingredientRepository;
    private readonly IngredientDetailService $service;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->ingredientRepository = $this->createMock(IngredientRepository::class);
        $this->service = new IngredientDetailService($this->ingredientRepository);
    }

    /**
     * @throws Exception
     */
    public function testExecuteReturnsSuccess(): void
    {
        $ingredient = $this->createMock(Ingredient::class);
        $this->ingredientRepository->method('find')->willReturn($ingredient);

        $result = $this->service->execute(1);

        $this->assertTrue($result['success']);
        $this->assertSame($ingredient, $result['ingredient']);
    }

    /**
     * @throws Exception
     */
    public function testExecuteReturnsFailure(): void
    {
        $this->ingredientRepository->method('find')->willReturn(null);

        $result = $this->service->execute(1);

        $this->assertFalse($result['success']);
        $this->assertEquals('Ingredient not found', $result['errors']);
    }
}
