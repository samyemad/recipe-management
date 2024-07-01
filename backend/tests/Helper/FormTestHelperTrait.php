<?php

namespace App\Tests\Helper;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;

trait FormTestHelperTrait
{
    private function configureForm(FormInterface $form, bool $isValid, array $errors = []): void
    {
        $form->method('submit')->willReturnSelf();
        $form->method('isSubmitted')->willReturn(true);
        $form->method('isValid')->willReturn($isValid);

        $errorIterator = new FormErrorIterator($form, $errors);
        $form->method('getErrors')->willReturn($errorIterator);
        $form->method('all')->willReturn([]);
    }
}
