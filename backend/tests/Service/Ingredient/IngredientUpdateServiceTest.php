<?php

namespace App\Tests\Service\Ingredient;

use App\Entity\Ingredient\Ingredient;
use App\Service\Ingredient\IngredientUpdateService;
use App\Tests\Helper\FormTestHelperTrait;
use App\Trait\FormErrorsTrait;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class IngredientUpdateServiceTest extends TestCase
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
        $this->configureForm(
            $this->form,
            true
        );
        $service = new IngredientUpdateService($this->entityManager, $this->formFactory);
        $ingredient = new Ingredient();
        $ingredientData = ['name' => 'Updated Tomato', 'nutritionalInformation' => []];
        $result = $service->execute($ingredient, $ingredientData);

        $this->assertTrue($result['success']);
    }

    public function testExecuteReturnsFailure(): void
    {
        $this->configureForm(
            $this->form,
            false,
            [new FormError('The ingredient name must not be blank.')]
        );

        $service = new class($this->entityManager, $this->formFactory) extends IngredientUpdateService {
            use FormErrorsTrait;
        };
        $ingredient = new Ingredient();
        $ingredientData = ['name' => '', 'nutritionalInformation' => []];
        $result = $service->execute($ingredient, $ingredientData);

        $this->assertFalse($result['success']);
        $this->assertEquals(['form' => ['The ingredient name must not be blank.']], $result['errors']);
    }
}
