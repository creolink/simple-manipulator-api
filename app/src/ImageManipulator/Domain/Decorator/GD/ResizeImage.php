<?php

namespace App\ImageManipulator\Domain\Decorator\GD;

use App\ImageManipulator\Domain\Decorator\ResizeImageInterface;
use App\ImageManipulator\Domain\ValueObject\Height;
use App\ImageManipulator\Domain\ValueObject\Width;

class ResizeImage extends Decorator implements ResizeImageInterface
{
    private readonly Width $width;
    private readonly Height $height;

    public function __construct(
        protected GDImageManipulatorInterface $component,
        private readonly array $parameters
    ) {
        parent::__construct($component);

        $this->resolveParameters();
    }

    public function modifyImage(): \GdImage
    {
        $currentImage = parent::modifyImage();
        $currentImageWidth = imagesx($currentImage);
        $currentImageHeight = imagesy($currentImage);

        $newImage = imagecreatetruecolor($this->width->value(), $this->height->value());

        imagecopyresampled(
            $newImage,
            $currentImage,
            0,
            0,
            0,
            0,
            $this->width->value(),
            $this->height->value(),
            $currentImageWidth,
            $currentImageHeight
        );

        return $newImage;
    }

    public static function can(string $modificator, array $parameters): bool
    {
        return 'resize' == $modificator && sizeof($parameters) >= 2;
    }

    private function resolveParameters(): void
    {
        $this->width = Width::toInt($this->parameters[0]);
        $this->height = Height::toInt($this->parameters[1]);
    }
}
