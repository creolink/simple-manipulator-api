<?php

namespace App\ImageManipulator\Domain\ValueObject;

use App\ImageManipulator\Domain\ValueObject\Exception\InvalidParameterValueException;
use App\ImageManipulator\Domain\ValueObject\Shared\IntValueObject;

class Width extends IntValueObject
{
    public static function toInt(mixed $value): static
    {
        $intValue = parent::toInt($value);

        if ($intValue->value() != $value || $intValue->value() < 0) {
            throw new InvalidParameterValueException(sprintf('Parameter %s must have value more then 0', static::class));
        }

        return $intValue;
    }
}
