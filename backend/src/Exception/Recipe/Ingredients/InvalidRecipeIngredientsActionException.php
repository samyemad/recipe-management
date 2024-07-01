<?php

namespace App\Exception\Recipe\Ingredients;

class InvalidRecipeIngredientsActionException extends \Exception
{
    public function __construct(string $action)
    {
        parent::__construct("Invalid action: $action");
    }
}
