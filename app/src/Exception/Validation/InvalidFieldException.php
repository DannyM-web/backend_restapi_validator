<?php

namespace Acme\Exception\Validation;

use Exception;

class InvalidFieldException extends ValidatorException
{
    public function __construct($field)
    {
        parent::__construct("The field $field is invalid");
    }
}
