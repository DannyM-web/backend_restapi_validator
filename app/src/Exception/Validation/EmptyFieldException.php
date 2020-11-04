<?php

namespace Acme\Exception\Validation;

use Exception;

class EmptyFieldException extends ValidatorException
{
    public function __construct($field)
    {
        parent::__construct("The field $field is empty");
    }
}
