<?php

namespace App\ImageManipulator\Domain\ValueObject\Shared;

class FloatValueObject implements ValueObjectInterface
{
    private function __construct(private readonly float $value)
    {
    }

    public static function toFloat(mixed $value): static
    {
        return new static((float) $value);
    }

    public function value(): float
    {
        return $this->value;
    }

    public function roundedValue($precision): float
    {
        return round($this->value, $precision);
    }
}
