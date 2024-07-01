<?php

namespace App\Tests\Service\Recipe;

use App\Entity\Recipe\Recipe;
use App\Service\Recipe\RecipeCreationService;
use App\Tests\Helper\FormTestHelperTrait;
use App\Trait\FormErrorsTrait;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class RecipeCreationServiceTest extends TestCase
{
    use FormTestHelperTrait;

    private readonly EntityManagerInterface $entityManager;
    private readonly FormFactoryInterface $formFactory;
    private readonly FormInterface $form;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->form = $this->createMock(FormInterface::class);
        $this->formFactory->method('create')->willReturn($this->form);
    }

    public function testExecuteReturnsSuccess(): void
    {
        $this->configureForm($this->form, true);

        $service = new RecipeCreationService($this->entityManager, $this->formFactory);

        $recipeData = ['name' => 'New Recipe', 'description' => 'Delicious recipe'];
        $result = $service->execute($recipeData);

        $this->assertTrue($result['success']);
        $this->assertInstanceOf(Recipe::class, $result['recipe']);
    }

    public function testExecuteReturnsFailureDueToValidationError(): void
    {
        $this->configureForm($this->form, false, [new FormError('The recipe name must not be blank.')]);

        $service = new class($this->entityManager, $this->formFactory) extends RecipeCreationService {
            use FormErrorsTrait;
        };

        $recipeData = ['name' => '', 'description' => ''];
        $result = $service->execute($recipeData);

        $this->assertFalse($result['success']);
        $this->assertEquals(['form' => ['The recipe name must not be blank.']], $result['errors']);
    }
}
