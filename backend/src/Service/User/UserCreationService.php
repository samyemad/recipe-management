<?php

namespace App\Service\User;

use App\Entity\User\User;
use App\Form\User\UserType;
use App\Trait\FormErrorsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCreationService implements UserCreationServiceInterface
{
    use FormErrorsTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FormFactoryInterface $formFactory,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function execute(array $userData): array
    {
        $user = new User();
        $form = $this->formFactory->create(UserType::class, $user);
        $form->submit($userData);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return ['success' => true, 'user' => $user];
        }

        return ['success' => false, 'errors' => $this->getFormErrors($form)];
    }
}
