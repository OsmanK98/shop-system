<?php

namespace App\Utils;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HandleError
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function isValidatedObject($object)
    {
        $errors = $this->validator->validate($object);
        if ($errors->count() > 0) {
            return $this->format($errors);
        }
        return true;
    }

    public function format(ConstraintViolationListInterface $errors): array
    {
        $validationError = [];
        foreach ($errors as $error) {
            $validationError[] = $error;
        }
        return $validationError;
    }
}
