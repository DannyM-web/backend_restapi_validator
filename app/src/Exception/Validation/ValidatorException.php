<?php

namespace Acme\Exception\Validation;

use Exception;

class ValidatorException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
