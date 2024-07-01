<?php

namespace App\Tests\Service\User;

use App\Entity\User\User;
use App\Service\User\UserCreationService;
use App\Tests\Helper\FormTestHelperTrait;
use App\Trait\FormErrorsTrait;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCreationServiceTest extends TestCase
{
    use FormTestHelperTrait;

    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;
    private UserPasswordHasherInterface $passwordHasher;
    private FormInterface $form;
    private UserCreationService $service;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->form = $this->createMock(FormInterface::class);
        $this->formFactory->method('create')->willReturn($this->form);
        $this->service = new UserCreationService($this->entityManager, $this->formFactory, $this->passwordHasher);
    }

    public function testExecuteReturnsSuccess(): void
    {
        $this->passwordHasher->method('hashPassword')->willReturn('hashed_password');

        $this->configureForm($this->form, true);

        $this->form->method('get')->willReturnSelf();
        $this->form->method('getData')->willReturn('password');

        $userData = ['email' => 'user@example.com', 'password' => 'password'];
        $result = $this->service->execute($userData);

        $this->assertTrue($result['success']);
        $this->assertInstanceOf(User::class, $result['user']);
        $this->assertEquals('hashed_password', $result['user']->getPassword());
    }

    public function testExecuteReturnsFailureDueToBlankEmail(): void
    {
        $this->configureForm($this->form, false, [new FormError('The email must not be blank.')]);

        $service = new class($this->entityManager, $this->formFactory, $this->passwordHasher) extends UserCreationService {
            use FormErrorsTrait;
        };

        $userData = ['email' => '', 'password' => 'password'];
        $result = $service->execute($userData);

        $this->assertFalse($result['success']);
        $this->assertEquals(['form' => ['The email must not be blank.']], $result['errors']);
    }

    public function testExecuteReturnsFailureDueToInvalidEmail(): void
    {
        $this->configureForm($this->form, false, [new FormError('Please enter a valid email address.')]);

        $service = new class($this->entityManager, $this->formFactory, $this->passwordHasher) extends UserCreationService {
            use FormErrorsTrait;
        };

        $userData = ['email' => 'invalid-email', 'password' => 'password'];
        $result = $service->execute($userData);

        $this->assertFalse($result['success']);
        $this->assertEquals(['form' => ['Please enter a valid email address.']], $result['errors']);
    }

    public function testExecuteThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Test exception');

        $this->entityManager->expects($this->once())->method('persist')->will($this->throwException(new \Exception('Test exception')));

        $this->configureForm($this->form, true);

        $this->form->method('get')->willReturnSelf();
        $this->form->method('getData')->willReturn('password');

        $service = new class($this->entityManager, $this->formFactory, $this->passwordHasher) extends UserCreationService {
            use FormErrorsTrait;
        };

        $userData = ['email' => 'user@example.com', 'password' => 'password'];
        $service->execute($userData);
    }
}
