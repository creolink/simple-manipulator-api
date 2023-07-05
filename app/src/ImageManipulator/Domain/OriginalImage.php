<?php

namespace App\ImageManipulator\Domain;

use App\ImageManipulator\Domain\Shared\AggregateRoot;
use App\ImageManipulator\Domain\ValueObject\ImageLibIdentifierInterface;
use App\ImageManipulator\Domain\ValueObject\ImageMimeType;
use App\ImageManipulator\Domain\ValueObject\ImageName;

class OriginalImage extends AggregateRoot
{
    public function __construct(
        private readonly ImageName $imageName,
        private readonly ImageLibIdentifierInterface $imageResource,
        private readonly ImageMimeType $imageMimeType,
        private readonly array $imageProperties = []
    ) {
    }

    public function getImageName(): ImageName
    {
        return $this->imageName;
    }

    public function getImageMimeType(): ImageMimeType
    {
        return $this->imageMimeType;
    }

    public function getImageResource(): ImageLibIdentifierInterface
    {
        return $this->imageResource;
    }

    public function getImageProperties(): array
    {
        return $this->imageProperties;
    }
}
