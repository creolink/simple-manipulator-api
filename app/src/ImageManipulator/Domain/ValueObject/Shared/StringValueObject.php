<?php

namespace App\ImageManipulator\Domain\ValueObject\Shared;

class StringValueObject implements ValueObjectInterface
{
    private function __construct(protected string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public static function stringify(mixed $value): static
    {
        return new static((string) $value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
