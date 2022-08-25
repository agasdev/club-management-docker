<?php

declare(strict_types=1);

namespace App\Packages\Common\Application\Services;

use Symfony\Component\Form\FormInterface;

class GetErrorsFromForm
{
    public function __invoke(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->__invoke($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}