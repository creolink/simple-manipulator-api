<?php

namespace App\ImageManipulator\Domain\Decorator\GD;

use App\ImageManipulator\Domain\Decorator\ImageDecoratorInterface;

abstract class Decorator implements ImageDecoratorInterface, GDImageManipulatorInterface
{
    public function __construct(protected GDImageManipulatorInterface $imageManipulator)
    {
    }

    public function getImage(): \GdImage
    {
        return $this->imageManipulator->getImage();
    }

    public function modifyImage(): \GdImage
    {
        return $this->imageManipulator->modifyImage();
    }
}
