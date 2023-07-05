<?php

namespace App\ImageManipulator\Domain\ValueObject\Exception;

use App\ImageManipulator\Infrastructure\Framework\Exception\BadRequestException;

class InvalidParameterValueException extends BadRequestException
{
    public function __construct($message = '', $previous = null)
    {
        parent::__construct($message);
    }
}
