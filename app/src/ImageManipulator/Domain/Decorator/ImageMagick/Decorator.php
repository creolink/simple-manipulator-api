<?php

namespace App\ImageManipulator\Domain\Decorator\ImageMagick;

use App\ImageManipulator\Domain\Decorator\ImageDecoratorInterface;

abstract class Decorator implements ImageDecoratorInterface, ImageMagickImageManipulatorInterface
{
    public function __construct(protected ImageMagickImageManipulatorInterface $imageManipulator)
    {
    }

    public function getImage(): \Imagick
    {
        return $this->imageManipulator->getImage();
    }

    public function modifyImage(): \Imagick
    {
        return $this->imageManipulator->modifyImage();
    }
}
