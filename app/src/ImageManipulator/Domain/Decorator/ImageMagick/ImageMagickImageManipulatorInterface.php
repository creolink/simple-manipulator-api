<?php

namespace App\ImageManipulator\Domain\Decorator\ImageMagick;

interface ImageMagickImageManipulatorInterface
{
    public function modifyImage(): \Imagick;

    public function getImage(): \Imagick;
}
