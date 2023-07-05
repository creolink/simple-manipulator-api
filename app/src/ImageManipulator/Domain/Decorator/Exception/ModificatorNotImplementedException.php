<?php

namespace App\ImageManipulator\Domain\Decorator\Exception;

use App\ImageManipulator\Infrastructure\Framework\Exception\NotAllowedException;

class ModificatorNotImplementedException extends NotAllowedException
{
    public function __construct($message = '', $previous = null)
    {
        parent::__construct($message);
    }
}
