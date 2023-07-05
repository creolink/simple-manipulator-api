<?php

namespace App\ImageManipulator\Infrastructure\Persistance\GDStrategies;

use App\ImageManipulator\Domain\FileRepositoryInterface;

class PNGFileHandler implements GDStrategiesInterface
{
    public function loadFile(string $imageName): \GdImage
    {
        return imagecreatefrompng(FileRepositoryInterface::SRC_IMAGE_FOLDER.$imageName);
    }

    public function saveFile(\GdImage $resource, string $imageFileName): void
    {
        imagepng($resource, $imageFileName);
    }

    public function can(string $mimeType): bool
    {
        return 'image/png' == $mimeType;
    }
}
