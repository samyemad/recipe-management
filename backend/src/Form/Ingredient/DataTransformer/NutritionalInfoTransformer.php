<?php

namespace App\Form\Ingredient\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transforms nutritional information data between model and form representations.
 *
 * @implements DataTransformerInterface<mixed, mixed>
 */
class NutritionalInfoTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): mixed
    {
        return $value;
    }

    public function reverseTransform(mixed $value): mixed
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        return $value;
    }
}
