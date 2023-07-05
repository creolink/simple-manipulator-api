<?php

namespace App\ImageManipulator\Domain\ValueObject\ImageMagick;

use App\ImageManipulator\Domain\ValueObject\ImageLibIdentifierInterface;

class ImageLibIdentifier implements ImageLibIdentifierInterface
{
    private function __construct(private readonly \Imagick $identifier)
    {
    }

    public static function create(mixed $identifier): static
    {
        return new static($identifier);
    }

    public function value(): \Imagick
    {
        return $this->identifier;
    }
}
