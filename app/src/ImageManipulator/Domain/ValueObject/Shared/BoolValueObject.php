<?php

namespace App\ImageManipulator\Domain\ValueObject\Shared;

class BoolValueObject implements ValueObjectInterface
{
    private function __construct(protected readonly bool $value)
    {
    }

    public static function toBool(mixed $value): static
    {
        return new static((bool) $value);
    }

    public function value(): int
    {
        return $this->value;
    }
}
