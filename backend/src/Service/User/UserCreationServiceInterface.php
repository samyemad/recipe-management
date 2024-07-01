<?php

namespace App\Service\User;

interface UserCreationServiceInterface
{
    public function execute(array $userData): array;
}
