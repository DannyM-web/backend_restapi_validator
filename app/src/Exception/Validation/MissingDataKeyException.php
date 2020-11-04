<?php

namespace Acme\Exception\Validation;

use Exception;

class MissingDataKeyException extends ValidatorException
{
    public function __construct($key)
    {
        parent::__construct("Payload Key missing: $key");
    }
}
