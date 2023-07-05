<?php

namespace App\ImageManipulator\Infrastructure\Persistance;

use App\ImageManipulator\Domain\OriginalImage;
use App\ImageManipulator\Domain\ValueObject\ImageLibIdentifierInterface;
use App\ImageManipulator\Domain\ValueObject\ImageMagick\ImageLibIdentifier;
use App\ImageManipulator\Domain\ValueObject\ImageMimeType;
use App\ImageManipulator\Domain\ValueObject\ImageName;

class ImageMagickFileRepository extends FileRepository
{
    public function getOriginalImage(ImageName $imageName): OriginalImage
    {
        if (!extension_loaded('imagick')) {
            throw new \RuntimeException('PHP Image Magick Library is missing');
        }

        $image = new \Imagick(realpath(self::SRC_IMAGE_FOLDER.$imageName->value()));

        return new OriginalImage(
            $imageName,
            ImageLibIdentifier::create($image),
            ImageMimeType::stringify($image->getImageMimeType()),
            $image->getImageProperties()
        );
    }

    public function saveModifiedImage(ImageLibIdentifierInterface $imageResource, string $imageName, string $mimeType): void
    {
        $file = fopen(self::TMP_IMAGE_FOLDER.$imageName, 'w+');
        $imageResource->value()->writeImageFile($file);
        fclose($file);
    }
}
