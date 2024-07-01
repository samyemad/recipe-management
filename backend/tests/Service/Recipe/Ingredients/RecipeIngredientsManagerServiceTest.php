<?php

namespace App\Tests\Service\Recipe\Ingredients;

use App\Entity\Recipe\Recipe;
use App\Service\Recipe\Ingredients\RecipeIngredientsHandlerServiceInterface;
use App\Service\Recipe\Ingredients\RecipeIngredientsManagerService;
use App\Tests\Helper\FormTestHelperTrait;
use App\Trait\FormErrorsTrait;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class RecipeIngredientsManagerServiceTest extends TestCase
{
    use FormTestHelperTrait;

    private readonly EntityManagerInterface $entityManager;
    private readonly FormFactoryInterface $formFactory;
    private readonly RecipeIngredientsHandlerServiceInterface $handlerService;
    private readonly FormInterface $form;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->handlerService = $this->createMock(RecipeIngredientsHandlerServiceInterface::class);
        $this->form = $this->createMock(FormInterface::class);
        $this->formFactory->method('create')->willReturn($this->form);
    }

    /**
     * @throws Exception
     */
    public function testExecuteReturnsSuccess(): void
    {
        $this->configureForm($this->form, true);

        $service = new RecipeIngredientsManagerService($this->entityManager, $this->formFactory, $this->handlerService);
        $recipe = new Recipe();
        $ingredientOperations = [
            ['action' => 'update', 'ingredient' => 1, 'quantity' => 10],
            ['action' => 'add', 'ingredient' => 2, 'quantity' => 30],
        ];
        $result = $service->execute($recipe, $ingredientOperations);
        $this->assertTrue($result['success']);
    }

    public function testExecuteReturnsFailureDueToValidationError(): void
    {
        $this->configureForm($this->form, false, [new FormError('Invalid Operation Action.')]);

        $service = new class($this->entityManager, $this->formFactory, $this->handlerService) extends RecipeIngredientsManagerService {
            use FormErrorsTrait;
        };
        $recipe = new Recipe();
        $ingredientOperations = [
            ['action' => 'anotherAction', 'ingredient' => 1, 'quantity' => 10],
        ];
        $result = $service->execute($recipe, $ingredientOperations);
        $this->assertFalse($result['success']);
        $this->assertEquals(['form' => ['Invalid Operation Action.']], $result['errors']);
    }
}
