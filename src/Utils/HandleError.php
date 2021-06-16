<?php

namespace App\Utils;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class HandleError
{
    static function format(ConstraintViolationListInterface $errors): array
    {
        $validationError = [];
        foreach ($errors as $error) {
            $validationError[] = $error;
        }
        return $validationError;
    }
}