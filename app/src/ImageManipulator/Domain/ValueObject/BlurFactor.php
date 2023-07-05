<?php

namespace App\ImageManipulator\Domain\ValueObject;

use App\ImageManipulator\Domain\ValueObject\Exception\InvalidParameterValueException;
use App\ImageManipulator\Domain\ValueObject\Shared\FloatValueObject;

class BlurFactor extends FloatValueObject
{
    public static function default(): static
    {
        return parent::toFloat(1);
    }

    public static function toFloat(mixed $value): static
    {
        $floatValue = parent::toFloat($value);

        if ($floatValue->value() < 0 || $floatValue->value() > 100) {
            throw new InvalidParameterValueException(sprintf('Parameter %s must have value between 0 and 100', static::class));
        }

        return $floatValue;
    }
}
