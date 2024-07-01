<?php

namespace App\Form\Recipe;

use App\Constants\Recipe\Ingredients\RecipeIngredientsAction;
use App\DTO\Recipe\RecipeIngredientsDTO;
use App\Entity\Ingredient\Ingredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->add('quantity', IntegerType::class, [
                'required' => false,
            ])
            ->add('action', ChoiceType::class, [
                'choices' => [
                    'Add' => RecipeIngredientsAction::ADD,
                    'Update' => RecipeIngredientsAction::UPDATE,
                    'Remove' => RecipeIngredientsAction::REMOVE,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredientsDTO::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }
}
