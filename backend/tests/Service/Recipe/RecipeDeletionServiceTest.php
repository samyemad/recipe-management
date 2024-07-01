<?php

namespace App\Tests\Service\Recipe;

use App\Entity\Recipe\Recipe;
use App\Service\Recipe\RecipeDeletionService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class RecipeDeletionServiceTest extends TestCase
{
    private readonly EntityManagerInterface $entityManager;
    private readonly RecipeDeletionService $service;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->service = new RecipeDeletionService($this->entityManager);
    }

    /**
     * @throws Exception
     */
    public function testExecuteRemovesRecipe(): void
    {
        $this->entityManager->expects($this->once())->method('remove');
        $this->entityManager->expects($this->once())->method('flush');

        $recipe = new Recipe();
        $this->service->execute($recipe);
    }

    /**
     * @throws Exception
     */
    public function testExecuteThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Test exception');

        $this->entityManager->expects($this->once())->method('remove')->will(
            $this->throwException(
                new \Exception('Test exception')
            )
        );
        $this->entityManager->expects($this->never())->method('flush');

        $recipe = new Recipe();
        $this->service->execute($recipe);
    }

    /**
     * @throws Exception
     */
    public function testExecuteThrowsExceptionOnFlush(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Test exception on flush');

        $this->entityManager->expects($this->once())->method('remove');
        $this->entityManager->expects($this->once())->method('flush')->will(
            $this->throwException(
                new \Exception('Test exception on flush')
            )
        );

        $recipe = new Recipe();
        $this->service->execute($recipe);
    }
}
