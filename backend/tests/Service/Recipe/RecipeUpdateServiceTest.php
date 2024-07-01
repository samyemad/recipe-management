<?php

namespace App\Tests\Service\Recipe;

use App\Entity\Recipe\Recipe;
use App\Service\Recipe\RecipeUpdateService;
use App\Tests\Helper\FormTestHelperTrait;
use App\Trait\FormErrorsTrait;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class RecipeUpdateServiceTest extends TestCase
{
    use FormTestHelperTrait;

    private readonly EntityManagerInterface $entityManager;
    private readonly FormFactoryInterface $formFactory;
    private readonly FormInterface $form;
    private readonly RecipeUpdateService $service;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->form = $this->createMock(FormInterface::class);
        $this->formFactory->method('create')->willReturn($this->form);
        $this->service = new RecipeUpdateService($this->entityManager, $this->formFactory);
    }

    public function testExecuteReturnsSuccess(): void
    {
        $this->configureForm($this->form, true);

        $recipe = new Recipe();
        $data = ['name' => 'Updated Recipe', 'description' => 'Updated description'];
        $result = $this->service->execute($recipe, $data);

        $this->assertTrue($result['success']);
    }

    public function testExecuteReturnsFailureDueToValidationError(): void
    {
        $this->configureForm($this->form, false, [new FormError('The recipe name must not be blank.')]);

        $service = new class($this->entityManager, $this->formFactory) extends RecipeUpdateService {
            use FormErrorsTrait;
        };

        $recipe = new Recipe();
        $data = ['name' => '', 'description' => 'Updated description'];
        $result = $service->execute($recipe, $data);

        $this->assertFalse($result['success']);
        $this->assertEquals(['form' => ['The recipe name must not be blank.']], $result['errors']);
    }

    public function testExecuteThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Test exception');

        $this->entityManager->method('flush')->will(
            $this->throwException(
                new \Exception('Test exception')
            )
        );
        $this->configureForm($this->form, true);

        $service = new class($this->entityManager, $this->formFactory) extends RecipeUpdateService {
            use FormErrorsTrait;
        };

        $recipe = new Recipe();
        $data = ['name' => 'Updated Recipe', 'description' => 'Updated description'];
        $service->execute($recipe, $data);
    }
}
