<?php

namespace App\ImageManipulator\Domain\ValueObject\Shared;

class IntValueObject implements ValueObjectInterface
{
    private function __construct(protected readonly int $value)
    {
    }

    public static function toInt(mixed $value): static
    {
        return new static((int) $value);
    }

    public function value(): int
    {
        return $this->value;
    }
}
