<?php

namespace App\Trait;

use Symfony\Component\Form\FormInterface;

trait FormErrorsTrait
{
    private function getFormErrors(FormInterface $form): array
    {
        $errors = [];
        $forms = [$form];
        while (! empty($forms)) {
            $currentForm = array_shift($forms);
            foreach ($currentForm->getErrors() as $error) {
                $formName = $currentForm->getName() ?: 'form';
                $errors[$formName][] = $error->getMessage();
            }
            foreach ($currentForm->all() as $child) {
                if ($child->isSubmitted() && ! $child->isValid()) {
                    $forms[] = $child;
                }
            }
        }

        return $errors;
    }
}
