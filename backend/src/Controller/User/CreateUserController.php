<?php

namespace App\Controller\User;

use App\Constants\User\UserSerializationGroups;
use App\Service\User\UserCreationServiceInterface;
use App\Trait\SerializerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CreateUserController extends AbstractController
{
    use SerializerTrait;

    #[Route('/create', name: 'api_user_create', methods: ['POST'])]
    public function __invoke(
        Request $request,
        UserCreationServiceInterface $userCreationService,
        SerializerInterface $serializer
    ): Response {
        $data = json_decode($request->getContent(), true);
        $registrationResult = $userCreationService->execute(
            $data
        );
        if ($registrationResult['success']) {
            return $this->serializeData(
                $serializer,
                $registrationResult['user'],
                UserSerializationGroups::USER_WRITE,
                [],
                Response::HTTP_CREATED
            );
        } else {
            return $this->json([
                'status' => 'error',
                'errors' => $registrationResult['errors'],
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
