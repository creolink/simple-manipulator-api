<?php

namespace App\ImageManipulator\Application;

use App\ImageManipulator\Domain\Shared\Bus\Query\ResponseInterface;

class ImageResponse implements ResponseInterface
{
    public function __construct(
        private readonly string $imageName,
        private readonly string $imageContent,
        private readonly string $imageMime,
    ) {
    }

    public function getImageName(): string
    {
        return $this->imageName;
    }

    public function getImageContent(): string
    {
        return $this->imageContent;
    }

    public function getImageMime(): string
    {
        return $this->imageMime;
    }
}
