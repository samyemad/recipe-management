<?php

namespace App\Form\Ingredient;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class NutritionalInfoType extends AbstractType
{
    /**
     * @param DataTransformerInterface<mixed, mixed> $nutritionalInfoTransformer
     */
    public function __construct(private readonly DataTransformerInterface $nutritionalInfoTransformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->nutritionalInfoTransformer);
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
