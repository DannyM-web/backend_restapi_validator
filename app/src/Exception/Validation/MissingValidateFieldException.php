<?php

namespace Acme\Exception\Validation;

use Exception;

class MissingValidateFieldException extends ValidatorException
{
    public function __construct($field)
    {
        parent::__construct("The field is missing: $field");
    }
}
