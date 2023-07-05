<?php

namespace App\ImageManipulator\Domain\ValueObject\GD;

use App\ImageManipulator\Domain\ValueObject\ImageLibIdentifierInterface;

class ImageLibIdentifier implements ImageLibIdentifierInterface
{
    private function __construct(private readonly \GdImage $identifier)
    {
    }

    public static function create(mixed $identifier): static
    {
        return new static($identifier);
    }

    public function value(): \GdImage
    {
        return $this->identifier;
    }
}
