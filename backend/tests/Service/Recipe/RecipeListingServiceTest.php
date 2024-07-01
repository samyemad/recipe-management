<?php

namespace App\Tests\Service\Recipe;

use App\Entity\Recipe\Recipe;
use App\Repository\Recipe\RecipeRepository;
use App\Service\Recipe\RecipeListingService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class RecipeListingServiceTest extends TestCase
{
    private readonly RecipeRepository $recipeRepository;
    private readonly RecipeListingService $service;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->recipeRepository = $this->createMock(RecipeRepository::class);
        $this->service = new RecipeListingService($this->recipeRepository);
    }

    public function testExecuteReturnsRecipesWithPagination(): void
    {
        $recipe1 = new Recipe();
        $recipe2 = new Recipe();
        $this->recipeRepository->method('findWithPagination')->willReturn([$recipe1, $recipe2]);
        $limit = 10;
        $offset = 0;
        $result = $this->service->execute($limit, $offset);
        $this->assertCount(2, $result);
        $this->assertSame([$recipe1, $recipe2], $result);
    }

    public function testExecuteReturnsEmptyArrayWithPagination(): void
    {
        $this->recipeRepository->method('findWithPagination')->willReturn([]);
        $limit = 10;
        $offset = 0;
        $result = $this->service->execute($limit, $offset);
        $this->assertEmpty($result);
    }
}
