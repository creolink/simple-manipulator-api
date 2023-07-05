<?php

namespace App\ImageManipulator\Infrastructure\Persistance\GDStrategies;

use App\ImageManipulator\Domain\FileRepositoryInterface;

class JPGFileHandler implements GDStrategiesInterface
{
    public function loadFile(string $imageName): \GdImage
    {
        return imagecreatefromjpeg(FileRepositoryInterface::SRC_IMAGE_FOLDER.$imageName);
    }

    public function saveFile(\GdImage $resource, string $imageFileName): void
    {
        imagejpeg($resource, $imageFileName);
    }

    public function can(string $mimeType): bool
    {
        return 'image/jpg' == $mimeType || 'image/jpeg' == $mimeType;
    }
}
