<?php

namespace App\ImageManipulator\Domain\ValueObject\ImageMagick;

use App\ImageManipulator\Domain\ValueObject\Exception\InvalidParameterValueException;
use App\ImageManipulator\Domain\ValueObject\Shared\IntValueObject;

class ImageFilter extends IntValueObject
{
    public static function default(): static
    {
        return static::toInt('FILTER_UNDEFINED');
    }

    public static function toInt(mixed $value): static
    {
        try {
            $valueFromConstant = constant('\Imagick::'.strtoupper($value));
        } catch (\Throwable $e) {
            throw new InvalidParameterValueException(sprintf('Parameter %s has invalid value. Reason: `%s`.', static::class, $e->getMessage()));
        }

        return parent::toInt($valueFromConstant);
    }
}
