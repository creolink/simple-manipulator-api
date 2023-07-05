<?php

namespace App\ImageManipulator\Domain\Decorator\ImageMagick;

use App\ImageManipulator\Domain\Decorator\ImageManipulatorInterface;

class ImageManipulator implements ImageManipulatorInterface, ImageMagickImageManipulatorInterface
{
    private \Imagick $imagick;

    public function setImageLibIdentifier($libIdentifier): void
    {
        if (!$libIdentifier instanceof \Imagick) {
            throw new \RuntimeException('Invalid LibIdentifier in '.static::class);
        }

        $this->imagick = $libIdentifier;
    }

    public function getImage(): \Imagick
    {
        return $this->imagick;
    }

    public function modifyImage(): \Imagick
    {
        return $this->getImage();
    }
}
