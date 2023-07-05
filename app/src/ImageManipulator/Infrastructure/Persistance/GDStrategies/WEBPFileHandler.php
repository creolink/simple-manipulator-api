<?php

namespace App\ImageManipulator\Infrastructure\Persistance\GDStrategies;

use App\ImageManipulator\Domain\FileRepositoryInterface;

class WEBPFileHandler implements GDStrategiesInterface
{
    public function loadFile(string $imageName): \GdImage
    {
        return imagecreatefromwebp(FileRepositoryInterface::SRC_IMAGE_FOLDER.$imageName);
    }

    public function saveFile(\GdImage $resource, string $imageFileName): void
    {
        imagewebp($resource, $imageFileName);
    }

    public function can(string $mimeType): bool
    {
        return 'image/webp' == $mimeType;
    }
}
