<?php

namespace App\ImageManipulator\Domain\Decorator\GD;

interface GDImageManipulatorInterface
{
    public function modifyImage(): \GdImage;

    public function getImage(): \GdImage;
}
