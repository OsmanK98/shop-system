<?php

namespace App\Utils;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class HandleError
{
    public function format(ConstraintViolationListInterface $errors): array
    {
        $validationError = [];
        foreach ($errors as $error) {
            $validationError[] = $error;
        }
        return $validationError;
    }
}