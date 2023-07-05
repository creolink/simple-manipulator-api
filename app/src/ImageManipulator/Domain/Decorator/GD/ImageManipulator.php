<?php

namespace App\ImageManipulator\Domain\Decorator\GD;

use App\ImageManipulator\Domain\Decorator\ImageManipulatorInterface;

class ImageManipulator implements ImageManipulatorInterface, GDImageManipulatorInterface
{
    private \GdImage $gdimage;

    public function setImageLibIdentifier($libIdentifier): void
    {
        if (!$libIdentifier instanceof \GdImage) {
            throw new \RuntimeException('Invalid LibIdentifier in '.static::class);
        }

        $this->gdimage = $libIdentifier;
    }

    public function getImage(): \GdImage
    {
        return $this->gdimage;
    }

    public function modifyImage(): \GdImage
    {
        return $this->getImage();
    }
}
