<?php

namespace App\ImageManipulator\Infrastructure\Http\Exception;

use App\ImageManipulator\Infrastructure\Framework\Exception\HttpNotFoundException;

class ImageNotFoundException extends HttpNotFoundException
{
    public function __construct($message = '', $previous = null)
    {
        parent::__construct($message);
    }
}
