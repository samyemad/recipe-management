<?php

namespace App\Tests\Service\Recipe;

use App\Entity\Recipe\Recipe;
use App\Repository\Recipe\RecipeRepository;
use App\Service\Recipe\RecipeDetailService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class RecipeDetailServiceTest extends TestCase
{
    private readonly RecipeRepository $recipeRepository;
    private readonly RecipeDetailService $service;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->recipeRepository = $this->createMock(RecipeRepository::class);
        $this->service = new RecipeDetailService($this->recipeRepository);
    }

    /**
     * @throws Exception
     */
    public function testExecuteReturnsSuccess(): void
    {
        $recipe = new Recipe();
        $this->recipeRepository->method('find')->willReturn($recipe);

        $result = $this->service->execute(1);

        $this->assertTrue($result['success']);
        $this->assertSame($recipe, $result['recipe']);
    }

    /**
     * @throws Exception
     */
    public function testExecuteReturnsFailure(): void
    {
        $this->recipeRepository->method('find')->willReturn(null);

        $result = $this->service->execute(1);

        $this->assertFalse($result['success']);
        $this->assertEquals('Recipe not found', $result['errors']);
    }
}
