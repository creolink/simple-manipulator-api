<?php

namespace App\ImageManipulator\Domain\ValueObject;

use App\ImageManipulator\Domain\ValueObject\Exception\InvalidParameterValueException;
use App\ImageManipulator\Domain\ValueObject\Shared\BoolValueObject;

class BestFitFlag extends BoolValueObject
{
    public static function default(): static
    {
        return parent::toBool(0);
    }

    public static function toBool(mixed $value): static
    {
        if ('false' !== $value && 'true' !== $value) {
            throw new InvalidParameterValueException(sprintf('Parameter %s can be only `true` or `false`', static::class));
        }

        return parent::toBool('true' == $value);
    }
}
