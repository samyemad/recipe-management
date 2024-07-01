<?php

namespace App\Tests\Service\Ingredient;

use App\Entity\Ingredient\Ingredient;
use App\Service\Ingredient\IngredientCreationService;
use App\Tests\Helper\FormTestHelperTrait;
use App\Trait\FormErrorsTrait;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class IngredientCreationServiceTest extends TestCase
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
        $service = new IngredientCreationService($this->entityManager, $this->formFactory);

        $ingredientData = ['name' => 'Tomato', 'nutritionalInformation' => []];
        $result = $service->execute($ingredientData);
        $this->assertTrue($result['success']);
        $this->assertInstanceOf(Ingredient::class, $result['ingredient']);
    }

    public function testExecuteReturnsFailure(): void
    {
        $this->configureForm($this->form, false, [new FormError('The ingredient name must not be blank.')]);

        $service = new class($this->entityManager, $this->formFactory) extends IngredientCreationService {
            use FormErrorsTrait;
        };
        $ingredientData = ['name' => '', 'nutritionalInformation' => []];
        $result = $service->execute($ingredientData);
        $this->assertFalse($result['success']);
        $this->assertEquals(['form' => ['The ingredient name must not be blank.']], $result['errors']);
    }
}
