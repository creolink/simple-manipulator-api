<?php

namespace App\ImageManipulator\Infrastructure\Persistance\GDStrategies;

use App\ImageManipulator\Domain\FileRepositoryInterface;

class GIFFileHandler implements GDStrategiesInterface
{
    public function loadFile(string $imageName): \GdImage
    {
        return imagecreatefromgif(FileRepositoryInterface::SRC_IMAGE_FOLDER.$imageName);
    }

    public function saveFile(\GdImage $resource, string $imageFileName): void
    {
        imagegif($resource, $imageFileName);
    }

    public function can(string $mimeType): bool
    {
        return 'image/gif' == $mimeType;
    }
}
