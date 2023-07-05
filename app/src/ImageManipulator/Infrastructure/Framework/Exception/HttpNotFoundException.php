<?php

namespace App\ImageManipulator\Infrastructure\Framework\Exception;

class HttpNotFoundException extends \RuntimeException
{
    public function __construct($message = '', $code = 0, $previous = null)
    {
        parent::__construct($message, 404);
    }
}
