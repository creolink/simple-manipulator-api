<?php

namespace App\ImageManipulator\Domain\Decorator\Exception;

use App\ImageManipulator\Infrastructure\Framework\Exception\BadRequestException;

class InvalidModificatorException extends BadRequestException
{
    public function __construct($message = '', $previous = null)
    {
        parent::__construct($message);
    }
}
