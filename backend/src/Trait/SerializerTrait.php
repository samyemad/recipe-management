<?php

namespace App\Trait;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

trait SerializerTrait
{
    public function serializeData(
        SerializerInterface $serializer,
        mixed $data,
        string $group,
        array $additionalContext = [],
        int $statusCode = Response::HTTP_OK
    ): Response {
        $context = [
            'groups' => [$group],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return method_exists($object, 'getId') ? $object->getId() : null;
            },
        ];
        $context = array_merge($context, $additionalContext);

        $jsonContent = $serializer->serialize($data, 'json', $context);

        return new Response($jsonContent, $statusCode, ['Content-Type' => 'application/json']);
    }
}
