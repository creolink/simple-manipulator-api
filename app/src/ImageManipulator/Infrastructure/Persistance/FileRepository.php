<?php

namespace App\ImageManipulator\Infrastructure\Persistance;

use App\ImageManipulator\Domain\FileRepositoryInterface;
use App\ImageManipulator\Infrastructure\Http\Exception\ImageNotFoundException;

abstract class FileRepository implements FileRepositoryInterface
{
    public function getModifiedImage(string $imageName): string
    {
        return file_get_contents(self::TMP_IMAGE_FOLDER.$imageName);
    }

    public function getModifiedImageData(string $imageName): array
    {
        return getimagesize(self::TMP_IMAGE_FOLDER.$imageName);
    }

    public function locateOriginalImage(string $imageName): void
    {
        $this->locateImage(FileRepositoryInterface::SRC_IMAGE_FOLDER, $imageName);
    }

    public function locateModifiedImage(string $imageName): void
    {
        $this->locateImage(FileRepositoryInterface::TMP_IMAGE_FOLDER, $imageName);
    }

    private function locateImage(string $imagePath, string $imageName): void
    {
        if (!file_exists($imagePath.$imageName)) {
            throw new ImageNotFoundException(sprintf("Image {$imageName} not found!"));
        }
    }
}
