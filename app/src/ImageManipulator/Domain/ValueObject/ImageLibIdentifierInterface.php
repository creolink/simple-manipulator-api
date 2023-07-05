<?php

namespace App\ImageManipulator\Domain\ValueObject;

use App\ImageManipulator\Domain\ValueObject\Shared\ValueObjectInterface;

interface ImageLibIdentifierInterface extends ValueObjectInterface
{
    public function value(): mixed;

    public static function create(mixed $identifier): static;
}
