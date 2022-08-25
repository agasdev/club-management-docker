<?php

namespace App\Packages\Common\Application\Services;

use App\Packages\Common\Application\Exception\InvalidResourceException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateAndValidateForm
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private GetErrorsFromForm $getErrorsFromForm
    )
    {
    }

    /**
     * @throws InvalidResourceException
     */
    public function __invoke(
        Request $request,
        string $class,
        mixed $dto
    )
    {
        $form = $this->formFactory->create($class, $dto);
        $form->submit(json_decode($request->getContent(), true));
        if (!$form->isValid()) {
            throw new InvalidResourceException(json_encode([
                'type' => 'validation_error',
                'errors' => ($this->getErrorsFromForm)($form)
            ]));
        }

        return $dto;
    }

}